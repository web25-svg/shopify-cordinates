<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


Route::get('/', function () {
Log::info('Webhook received before signature check');
});




Route::post('/shopify/webhook/order-pai2d', function(Request $request) {
    Log::info('Webhook hit!');
    Log::info($request->getContent());
    return response()->json(['status' => 'ok']);
});
