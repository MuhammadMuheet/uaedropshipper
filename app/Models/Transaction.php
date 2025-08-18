<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function paymentRequest()
    {
        return $this->belongsTo(PaymentRequest::class, 'payment_request_id');
    }
}
