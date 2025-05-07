<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use Searchable;

    public $timestamps = false;

    protected $fillable = ['sku', 'description', 'size', 'photo', 'updated_at'];

    public function toSearchableArray(): array
    {
        return [
            'description' => $this->description,
        ];
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    public function stockInCity(int $cityId): int
    {
        return $this->stocks->firstWhere('city_id', $cityId)?->stock ?? 0;
    }

}
