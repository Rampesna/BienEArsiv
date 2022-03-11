<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'subscription'
    ];

    public function apiToken()
    {
        return $this->api_token;
    }

    public function theme()
    {
        return $this->theme;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getSubscriptionAttribute()
    {
        return CustomerSubscription::where('customer_id', $this->customer_id)->orderBy('created_at', 'desc')->first();
    }
}
