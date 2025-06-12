<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productVariation()
    {
        return $this->belongsTo(productVariation::class, 'product_variation_id');
    }
    public function productStockBatch()
    {
        return $this->belongsTo(ProductStockBatch::class, 'batch_id');
    }
}
