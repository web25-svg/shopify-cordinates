<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FinalEntry;
use App\Models\JudgeEntry;
use App\Models\Winner;
use Illuminate\Http\Request;
class WinnerController extends Controller

{
   public function trigger(Request $request)
{
    $match = $request->input('match_id');

    $votes = JudgeEntry::where('product_id', $match)->get();
    if ($votes->isEmpty()) {
        return response()->json(['error'=>'No judge data'], 404);
    }
        
    $avgX = $votes->avg('x');
    $avgY = $votes->avg('y');

    $players = FinalEntry::where('product_id', $match)->get();
    if ($players->isEmpty()) {
        return response()->json(['error'=>'No players for match'], 404);
    }

    $results = [];
    foreach ($players as $p) {
        $dx = $p->x - $avgX;
        $dy = $p->y - $avgY;
        $distance = sqrt($dx*$dx + $dy*$dy);
        $results[] = [
            'entry_id' => $p->id,
            'player_id' => $p->player_id,
            'distance' => $distance,
            'amount_paid' => $p->amount_paid,
            'paid_at' => $p->created_at,
            'x' => $p->x,
            'y' => $p->y,
        ];
    }

    usort($results, fn($a, $b) => $a['distance'] <=> $b['distance']);

    // 6) optional: apply tie-breakers and save winners
    // tie-breaker order: (1) smaller distance already sorted (2) higher amount_paid (3) earlier paid_at
    $rank = 1;
    foreach ($results as $r) {
       Winner::create([
            'player_id' => $r['player_id'],
            'entry_id'  => $r['entry_id'],
            'product_id'=> $match,
            'rank'      => $rank,
            'distance'  => $r['distance'],
        ]);
        $rank++;
    }

    return response()->json([
        'correct' => ['x' => $avgX, 'y' => $avgY],
        'rankings' => $results
    ]);
}


}
