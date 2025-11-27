<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FinalEntry;
use App\Models\JudgeEntry;
use App\Models\Winner;
use Illuminate\Http\Request;

class WinnerController extends Controller
{
    public function getOrCalculateWinners(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $productId = $request->product_id;

        $winners = Winner::where('product_id', $productId)
            ->orderBy('rank', 'ASC')
            ->get();

        if ($winners->isEmpty()) {

            $votes = JudgeEntry::where('product_id', $productId)->get();
            if ($votes->isEmpty()) {
                return response()->json(['error' => 'No judge data'], 404);
            }

            $avgX = $votes->avg('x');
            $avgY = $votes->avg('y');

            $players = FinalEntry::where('product_id', $productId)->get();
            if ($players->isEmpty()) {
                return response()->json(['error' => 'No players for this match'], 404);
            }

            $results = [];
            foreach ($players as $p) {
                $dx = $p->x - $avgX;
                $dy = $p->y - $avgY;
                $distance = sqrt($dx * $dx + $dy * $dy);
                $results[] = [
                    'entry_id' => $p->id,
                    'session_token' => $p->session_token,
                    'distance' => $distance,
                    'amount_paid' => $p->amount_paid,
                    'paid_at' => $p->created_at,
                    'x' => $p->x,
                    'y' => $p->y
                ];
            }

            usort($results, function ($a, $b) {
                if ($a['distance'] != $b['distance']) return $a['distance'] <=> $b['distance'];
                if ($a['amount_paid'] != $b['amount_paid']) return $b['amount_paid'] <=> $a['amount_paid'];
                return $a['paid_at'] <=> $b['paid_at'];
            });

            $rank = 1;
            foreach ($results as $r) {
                Winner::create([
                    'session_token' => $r['session_token'],
                    'entry_id' => $r['entry_id'],
                    'product_id' => $productId,
                    'rank' => $rank,
                    'distance' => $r['distance'],
                    'x' => $r['x'],
                    'y' => $r['y']
                ]);
                $rank++;
            }

            $winners = Winner::where('product_id', $productId)
                ->orderBy('rank', 'ASC')
                ->get();
        }

        $response = [];
        foreach ($winners as $w) {
            $entry = FinalEntry::find($w->entry_id);
            $response[] = [
                'rank' => $w->rank,
                'session_token' => $w->session_token,
                'entry_id' => $w->entry_id,
                'x' => $entry->x ?? null,
                'y' => $entry->y ?? null
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $response
        ]);
    }
}
