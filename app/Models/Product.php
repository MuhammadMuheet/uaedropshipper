<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function variations()
{
    return $this->hasMany(productVariation::class);
}

public function batches()
{
    return $this->hasMany(ProductStockBatch::class);
}
}
