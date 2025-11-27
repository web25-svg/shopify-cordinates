<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempEntry;

class TempEntryController extends Controller
{
    // Get temporary entries by session token
    public function getTempEntries(Request $request)
    {
        $request->validate([
            'session_token' => 'required|string'
        ]);

        $entries = TempEntry::where('session_token', $request->session_token)->get();

        return response()->json([
            'status' => 'success',
            'data' => $entries
        ]);
    }

    // Create temporary entry
    public function store(Request $request)
    {
        $data = $request->validate([
            // 'session_token' => 'required|string',
            'product_id' => 'required|string',
            'coordinates.x' => 'required|numeric',
            'coordinates.y' => 'required|numeric',
        ]);

        $entry = TempEntry::create([
            'product_id' => $data['product_id'],
            'x' => $data['coordinates']['x'],
            'y' => $data['coordinates']['y'],
        ]);

        return response()->json(['status' => 'success']);
    }
}
