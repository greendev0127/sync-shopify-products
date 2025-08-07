<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Make sure to import your Product model

class ShopifyController extends Controller
{
    public function getProducts()
    {
        // Fetch all products from the 'products' table
        $products = Product::all();

        // Return the products as a JSON response
        return response()->json([
            'products' => $products
        ]);
    }
}