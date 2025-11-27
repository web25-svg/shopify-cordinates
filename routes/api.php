<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use App\Http\Controllers\API\TempEntryController;
use App\Http\Controllers\API\FinalEntryController;
use App\Http\Controllers\API\ShopifyWebhookController;
use App\Http\Controllers\API\JudgeController;
use App\Http\Controllers\API\WinnerController;
use App\Http\Controllers\API\SessionController;




Route::get('/session-entries', [SessionController::class, 'getEntriesBySessionToken']);

// Unity APIs
Route::post('/entries/temp', [TempEntryController::class, 'store']);
// Route::post('/entries/final', [FinalEntryController::class, 'store']);

// Shopify webhook
Route::post('/shopify/webhook/order-paid', [ShopifyWebhookController::class, 'orderPaid']);

// Judges
Route::post('/judges/submit', [JudgeController::class, 'submit']);

// Admin winner trigger
Route::get('/get-the-winner', [WinnerController::class, 'getOrCalculateWinners']);


Route::get('/temp-entries', [TempEntryController::class, 'getTempEntries']);

Route::get('/judges/entries', [JudgeController::class, 'getEntriesForJudging']);

