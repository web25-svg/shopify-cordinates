<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FinalEntry;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Get entries by session token
     */
    public function getEntriesBySessionToken(Request $request)
    {
        $request->validate([
            'session_token' => 'required|string'
        ]);

        $token = $request->session_token;
      
        return response()->json([
            'token' => $token
        ]);
    }
}
