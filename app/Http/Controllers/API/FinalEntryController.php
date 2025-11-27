<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempEntry;
use App\Models\FinalEntry;

class FinalEntryController extends Controller
{
    // Convert temp entry to final entry after Shopify payment
    public function store(Request $request)
    {
        $data = $request->validate([
            'temp_entry_id' => 'required|integer|exists:temp_entries,id',
            'order_id' => 'required|string',
            'customer_email' => 'required|email',
            'amount_paid' => 'required|numeric'
        ]);

        $temp = TempEntry::findOrFail($data['temp_entry_id']);
        
        $final = FinalEntry::create([
            'session_token' => $temp->session_token,
            'product_id' => $temp->product_id,
            'order_id' => $data['order_id'],
            'customer_email' => $data['customer_email'],
            'x' => $temp->x,
            'y' => $temp->y,
            'amount_paid' => $data['amount_paid']
        ]);

        return response()->json(['status'=>'success']);
    }
}
