<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopifySyncController;
use App\Http\Controllers\ShopifyController;

Route::post('/sync-products', ShopifySyncController::class);
Route::get('/products', [ShopifyController::class, 'getProducts']);