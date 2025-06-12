<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productVariation extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product()
{
    return $this->belongsTo(Product::class);
}

public function batches()
{
    return $this->hasMany(ProductStockBatch::class);
}
}
