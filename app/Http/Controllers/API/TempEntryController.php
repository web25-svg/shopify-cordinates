<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempEntry;

class TempEntryController extends Controller
{
    // Create temporary entry
    public function store(Request $request)
    {
        $data = $request->validate([
            'player_id' => 'required|string',
            'session_token' => 'required|string',
            'product_id' => 'required|string',
            'coordinates.x' => 'required|numeric',
            'coordinates.y' => 'required|numeric',
            // 'entries_count' => 'required|integer|min:1'
        ]);

        $entry = TempEntry::create([
            'player_id' => $data['player_id'],
            'session_token' => $data['session_token'],
            'product_id' => $data['product_id'],
            'x' => $data['coordinates']['x'],
            'y' => $data['coordinates']['y'],
            // 'entries_count' => $data['entries_count']
        ]);

        return response()->json(['status'=>'success']);
    }
}
