<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    const SELLER = 'seller';
    const SUB_SELLER = 'sub_seller';
    const ADMIN = 'admin';
    const SUB_ADMIN = 'sub_admin';
    const LOGISTIC_COMPANY = 'logistic_company';
    const DRIVER = 'driver';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'seller_id',
        'company_id',
        'name',
        'unique_id',
        'email',
        'store_name',
        'average_orders',
        'whatsapp',
        'mobile',
        'dropshipping_experience',
        'dropshipping_status',
        'bank',
        'ac_title',
        'ac_no',
        'iban',
        'type',
        'role',
        'password',
        'show_password',
        'status',
        'wallet',
        'shopify_domain',
        'shopify_token',
        'shopify_store_data'
    ];
    protected $guarded = ['id'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function serviceCharges()
    {
        return $this->hasMany(ServiceCharge::class);
    }
}