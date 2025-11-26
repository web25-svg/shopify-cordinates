<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use App\Http\Controllers\API\TempEntryController;
use App\Http\Controllers\API\FinalEntryController;
use App\Http\Controllers\API\ShopifyWebhookController;
use App\Http\Controllers\API\JudgeController;
use App\Http\Controllers\API\WinnerController;


// Route::post('/shopify', function (Request $request) {

//     // STEP 1: Verify Shopify Signature
//     $hmac = $request->header('X-Shopify-Hmac-Sha256');
//     $data = $request->getContent();
//     $calculatedHmac = base64_encode(hash_hmac('sha256', $data, env('SHOPIFY_SECRET'), true));

//     if (!hash_equals($hmac, $calculatedHmac)) {
//         Log::error('Shopify Webhook: Invalid HMAC Signature');
//         return response()->json(['message' => 'Invalid signature'], 401);
//     }

//     // STEP 2: Process Webhook Data
//     $payload = $request->all();
//     Log::info("Shopify Order Paid Webhook", $payload);

//     // Order::where('shopify_order_id', $payload['id'])
//     //       ->update(['payment_status' => 'paid']);

//     return response()->json(['status' => 'success'], 200);
// });



// Unity APIs
Route::post('/entries/temp', [TempEntryController::class, 'store']);
Route::post('/entries/final', [FinalEntryController::class, 'store']);

// Shopify webhook
Route::post('/shopify/webhook/order-paid', [ShopifyWebhookController::class, 'orderPaid']);

// Judges
Route::post('/judges/submit', [JudgeController::class, 'submit']);
// Route::post('/judges/calculate', [JudgeController::class, 'calculate']);

// Admin winner trigger
Route::post('/admin/calculate-winner', [WinnerController::class, 'trigger']);
