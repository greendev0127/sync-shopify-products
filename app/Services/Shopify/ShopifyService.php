<?php

namespace App\Services\Shopify;

use App\Models\Product;
use App\Models\ProductVariant;

class ShopifyService
{
    protected $client;

    public function __construct(ShopifyClient $client)
    {
        $this->client = $client;
    }

    public function syncProducts(): void
    {
        $response = $this->client->get('products.json');
        // dd($this->client->get('products.json')); 

        if ($response->successful()) {
            $products = $response->json()['products'];

            foreach ($products as $shopifyProduct) {
                // Sync the product
                $product = Product::updateOrCreate(
                    ['shopify_id' => $shopifyProduct['id']],
                    [
                        'title' => $shopifyProduct['title'],
                        'body_html' => $shopifyProduct['body_html'],
                        'vendor' => $shopifyProduct['vendor'],
                        'product_type' => $shopifyProduct['product_type'],
                        'handle' => $shopifyProduct['handle'],
                        'status' => $shopifyProduct['status'],
                        'image_src' => $shopifyProduct['image']['src'] ?? null,
                    ]
                );

                // Sync the variants for the product
                foreach ($shopifyProduct['variants'] as $shopifyVariant) {
                    ProductVariant::updateOrCreate(
                        ['shopify_id' => $shopifyVariant['id']],
                        [
                            'product_id' => $product->id,
                            'title' => $shopifyVariant['title'],
                            'sku' => $shopifyVariant['sku'],
                            'price' => $shopifyVariant['price'],
                            'position' => $shopifyVariant['position'],
                        ]
                    );
                }
            }
        }
    }
}