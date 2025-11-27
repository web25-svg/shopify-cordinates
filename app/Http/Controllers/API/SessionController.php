<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FinalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- add this import

class SessionController extends Controller
{
    /**
     * Get entries by session token
     */
    // public function getEntriesBySessionToken(Request $request)
    // {
    //     $request->validate([
    //         'session_token' => 'required|string'
    //     ]);

    //     $token = $request->session_token;
      
    //     return response()->json([
    //         'token' => $token
    //     ]);
    // }
    // getEntriesBySessionToken

    public function getEntriesBySessionToken()
    {
        // Generate a random, unique session token
        $token = Str::uuid()->toString(); // or Str::random(32) for alphanumeric

        return response()->json([
            'status' => 'success',
            'session_token' => $token
        ]);
    }


}
