<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStock extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['product_id', 'city_id', 'stock'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
