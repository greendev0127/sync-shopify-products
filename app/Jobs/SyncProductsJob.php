<?php

namespace App\Jobs;

use App\Services\Shopify\ShopifyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ShopifyService $shopifyService): void
    {
        // $shopifyService = new ShopifyService();

        // 1. Fetch products from Shopify
        $products = $shopifyService->syncProducts();

        // 2. Check if products were returned and log them for debugging
        if (! empty($products)) {
            \Log::info('Products received from Shopify: ', $products);
            
            // 3. Loop through the products and save each one to the database
            foreach ($products as $shopifyProduct) {
                Product::updateOrCreate(
                    ['shopify_id' => $shopifyProduct['id']],
                    [
                        'name' => $shopifyProduct['title'],
                        'price' => $shopifyProduct['variants'][0]['price'],
                        // ... other product fields
                    ]
                );
            }
        } else {
            \Log::info('No products found on Shopify.');
        }
    }
}