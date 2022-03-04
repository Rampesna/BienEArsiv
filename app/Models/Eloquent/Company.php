<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'balance'
    ];

    public function getBalanceAttribute()
    {
        $transactions = Transaction::where('company_id', $this->id)->get();
        return $transactions->where('direction', 0)->sum('amount') - $transactions->where('direction', 1)->sum('amount');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
