<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudgeEntry;
use App\Models\FinalEntry;

class JudgeController extends Controller
{
    // Tool 1: judges submit coordinates
    public function submit(Request $request)
    {
        $data = $request->validate([
            'judge_id' => 'required|string',
            'product_id' => 'required',
            'x' => 'required|numeric',
            'y' => 'required|numeric'
        ]);

        JudgeEntry::create($data);

        return response()->json(['status'=>'success']);
    }

    // // Tool 2: calculate winners
    // public function calculate()
    // {
    //     $entries = FinalEntry::with('judge_entries')->get();
    //     $results = [];

    //     foreach ($entries as $entry) {
    //         $avgX = $entry->judge_entries->avg('x');
    //         $avgY = $entry->judge_entries->avg('y');
    //         $distance = sqrt($avgX*$avgX + $avgY*$avgY);

    //         $results[] = [                                  
    //             'entry_id' => $entry->id,
    //             'player_id' => $entry->player_id,
    //             'distance' => $distance,
    //             'amount_paid' => $entry->amount_paid,
    //             'timestamp' => $entry->created_at
    //         ];
    //     }

    //     usort($results, fn($a,$b) => $a['distance'] <=> $b['distance']);

    //     return response()->json($results);
    // }
}
