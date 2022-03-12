<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'subscription'
    ];

    public function subscriptions()
    {
        return $this->hasMany(CustomerSubscription::class);
    }

    public function getSubscriptionAttribute()
    {
        return $this
            ->subscriptions()
            ->with([
                'subscription',
                'payment'
            ])
            ->where('subscription_expiry_date', '>=', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
