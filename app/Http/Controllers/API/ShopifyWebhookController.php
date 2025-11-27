<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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


        $order = json_decode($payload, true);

        $sessionToken = null;
        if (isset($order['note_attributes'])) {
            foreach ($order['note_attributes'] as $attr) {
                if (isset($attr['name']) && $attr['name'] === 'session_token') {
                    $sessionToken = $attr['value'];
                    break;
                }
            }
        }

        $temp = TempEntry::latest()->first();

        if ($temp) {
        FinalEntry::create([
            'session_token' => $sessionToken ?? 'no-session',
            'product_id' => $temp->product_id,
            'order_id' => $order['id'],
            'customer_email' => $order['customer_email'] ?? null,
            'x' => $temp->x,
            'y' => $temp->y,
            'amount_paid' => $order['total_price'] ?? 0
        ]);

            // $temp->delete();
        }

        return response()->json(['status' => 'ok']);
    }
}
