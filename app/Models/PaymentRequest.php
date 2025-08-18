<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'amount',
        'status',
    ];

    /**
     * Relationship: A payment request belongs to a seller (user)
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }


    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'payment_request_id');
    }
}
