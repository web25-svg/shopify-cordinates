<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudgeEntry;
use App\Models\FinalEntry;

class JudgeController extends Controller
{
    // Get entries for judging
    public function getEntriesForJudging(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $entries = FinalEntry::where('product_id', $request->product_id)->get();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // Judges submit coordinates
    public function submit(Request $request)
    {
        $data = $request->validate([
            'judge_id' => 'required|string',
            'product_id' => 'required|string',
            'x' => 'required|numeric',
            'y' => 'required|numeric'
        ]);

        JudgeEntry::create($data);

        return response()->json(['status'=>'success']);
    }
}
