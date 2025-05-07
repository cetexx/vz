<?php

namespace App\Services;

use App\Models\City;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Support\Facades\Log;

class ProductStockService
{
    public function import(array $items): void
    {
        foreach ($items as $item) {
            try {
                $sku = trim($item['sku']);
                $city = trim($item['city']);
                $stock = (int)$item['stock'];

                $product = Product::where('sku', $sku)->first();
                if (!$product) {
                    throw new \Exception('Product not found');
                }

                $city = City::firstOrCreate(['name' => $city]);

                ProductStock::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'city_id' => $city->id,
                    ],
                    [
                        'stock' => $stock,
                    ]
                );

            } catch (\Exception $e) {
                Log::error('Stock import failed SKU (' . $sku . '): ' . $e->getMessage());
            }
        }
    }
}
