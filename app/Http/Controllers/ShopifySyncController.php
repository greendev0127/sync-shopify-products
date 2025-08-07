<?php

namespace App\Http\Controllers;

use App\Jobs\SyncProductsJob;
use Illuminate\Http\Request;

class ShopifySyncController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        SyncProductsJob::dispatch();

        return response()->json([
            'message' => 'Product synchronization has been started.'
        ]);
    }
}