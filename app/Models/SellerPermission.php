<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPermission extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function SellerRoleName()
    {
        return $this->hasOne(SellerRole::class, 'id', 'role_id');
    }
}
