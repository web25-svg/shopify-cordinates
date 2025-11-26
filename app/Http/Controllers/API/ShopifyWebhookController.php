<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempEntry;
use App\Models\FinalEntry;
use Illuminate\Support\Facades\Log;

class ShopifyWebhookController extends Controller
{
    public function orderPaid(Request $request)
{
    $payload = $request->getContent();
    $hmac = $request->header('X-Shopify-Hmac-Sha256');
    $calculated = base64_encode(hash_hmac('sha256', $payload, env('SHOPIFY_SECRET'), true));

    Log::info("Shopify payload: ".$payload);

    // Uncomment to verify Shopify webhook
    // if (!hash_equals($hmac, $calculated)) {
    //     return response('Invalid signature', 401);
    // }

    $order = json_decode($payload, true);
    // Extract session_token from note_attributes
    $sessionToken = null;
    foreach ($order['note_attributes'] as $attr) {
        if (isset($attr['name']) && $attr['name'] === 'session_token') {
            $sessionToken = $attr['value'];
            break;
        }
    }
    if ($sessionToken) { // <-- use the correct variable
        $temp_entries = TempEntry::where('session_token', $sessionToken)->get();
        foreach ($temp_entries as $temp) {
            FinalEntry::create([
                'player_id' => $temp->player_id,
                'product_id' => $temp->product_id,
                'order_id' => $order['id'],
                'customer_email' => $order['customer_email'] ?? null,
                'x' => $temp->x,
                'y' => $temp->y,
                // 'entries_count' => $temp->entries_count,
                'amount_paid' => $order['total_price'] ?? 0
            ]);

            // $temp->delete(); // remove temp entry
        }
    }

    return response()->json(['status' => 'ok']);
}

}