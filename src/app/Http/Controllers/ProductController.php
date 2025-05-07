<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

/**
 * kai daug duomenu naudociau clickhouse filtravimui.
 * taip pat pagalvociau apie galimybe resource lygmeniu atlikti cache, taip pat ir handlinima kada turi issivalyti (kad nereiketu kiekviena karta viso cache valyti)
 * panaudojau scount nes per tech pokalbi buvo paminetas, buvo idomu ismeginti
 */
class ProductController extends Controller
{

    public function getAll(): JsonResponse
    {
        $products = Cache::remember('all_products', 60 * 60, function () {
            return Product::all();
        });

        return response()->json($products);
    }

    public function searchProduct(Request $request): JsonResponse
    {
        $searchTerm = $request->input('query');

        if (empty($searchTerm)) {
            return response()->json(['message' => 'Nurodykite paieškos žodį'], 400);
        }

        $cacheKey = 'product_search_' . Str::slug($searchTerm);

        $products = Cache::remember($cacheKey, 60 * 15, function () use ($searchTerm) {
            return Product::search($searchTerm)->get();
        });

        return response()->json($products);
    }
}
