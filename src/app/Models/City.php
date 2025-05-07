<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $table = 'cities';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function productStocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }
}
