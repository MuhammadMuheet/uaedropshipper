<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];
    // Relationship with User (Seller)
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Relationship with User (Sub-Seller)
    public function subSeller()
    {
        return $this->belongsTo(User::class, 'sub_seller_id');
    }
    public function logisticCompany()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    // Relationship with State
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    // Relationship with Area
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function service_charges()
    {
        return $this->belongsTo(ServiceCharge::class, 'service_charges_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

}
