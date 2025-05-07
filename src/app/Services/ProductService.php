<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function import(array $items): void
    {
        foreach ($items as $item) {
            try {
                $sku = trim($item['sku'] ?? '');
                $description = $item['description'] ?? null;
                $size = $item['size'] ?? null;
                $photo = $item['photo'] ?? null;
                $updatedAt = $item['updated_at'] ?? now();

                if (!$sku) {
                    throw new \Exception('Missing SKU');
                }

                $product = Product::where('sku', $sku)->first();

                if ($product && $product->updated_at >= $updatedAt) {
                    continue;
                }

                $product = Product::updateOrCreate(
                    ['sku' => $sku],
                    [
                        'description' => $description,
                        'size' => $size,
                        'photo' => $photo,
                        'updated_at' => $updatedAt,
                    ]
                );

                $product->searchable();

            } catch (\Exception $e) {
                Log::error('Product import failed SKU (' . $sku . '): ' . $e->getMessage());
            }
        }
    }
}
