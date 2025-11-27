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
        try {
            $payload = $request->getContent();
            $hmac = $request->header('X-Shopify-Hmac-Sha256');
            $calculated = base64_encode(hash_hmac('sha256', $payload, env('SHOPIFY_SECRET'), true));

            Log::info("Shopify payload: " . $payload);

            // Validate signature (optional)
            // if (!hash_equals($hmac, $calculated)) {
            //     return response('Invalid signature', 401);
            // }

            $order = json_decode($payload, true);

            // Extract session_token from note_attributes
            $sessionToken = null;

            if (!empty($order['note_attributes'])) {
                foreach ($order['note_attributes'] as $attr) {
                    if (isset($attr['name']) && $attr['name'] === 'session_token') {
                        $sessionToken = $attr['value'];
                        break;
                    }
                }
            }

            if ($sessionToken) {
                $temp_entries = TempEntry::where('session_token', $sessionToken)->get();

                foreach ($temp_entries as $temp) {
                    FinalEntry::create([
                        'session_token'   => $temp->session_token,
                        'product_id'      => $temp->product_id,
                        'order_id'        => $order['id'],
                        'customer_email'  => $order['customer']['email'] ?? null,
                        'x'               => $temp->x,
                        'y'               => $temp->y,
                        'amount_paid'     => $order['total_price'] ?? 0,
                    ]);
                }

                return response()->json(['status' => 'success']);
            }

            return response()->json([
                // 'status' => 'ignored',
                'message' => 'No session_token found'
            ]);

        } catch (\Exception $e) {
            Log::error('webhook error: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                // 'message' => 'Failed to process webhook',
                // 'details' => $e->getMessage()
            ], 500);
        }
    }
}
