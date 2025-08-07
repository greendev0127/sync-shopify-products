<?php

namespace App\Services\Shopify;

use Illuminate\Support\Facades\Http;

class ShopifyClient
{
    private $token;
    private $api_key;
    private $shop_name;
    private $api_version;

    public function __construct()
    {
        $this->token = env('SHOPIFY_ACCESS_TOKEN');
        $this->api_key = env('SHOPIFY_API_KEY');
        $this->shop_name = env('SHOPIFY_SHOP_NAME');
        $this->api_version = env('SHOPIFY_API_VERSION', '2024-07');
    }

    public function get($endpoint)
    {
        return Http::withHeaders([
            'X-Shopify-Access-Token' => $this->token,
        ])->baseUrl("https://{$this->shop_name}.myshopify.com/admin/api/{$this->api_version}")
          ->withOptions([
              'verify' => false, // This is the line to add
          ])
          ->get($endpoint);
    }
}