<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockBatch extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product()
{
    return $this->belongsTo(Product::class);
}

public function variation()
{
    return $this->belongsTo(productVariation::class);
}
}
