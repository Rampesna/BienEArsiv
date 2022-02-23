<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Safebox extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'balance'
    ];

    public function type()
    {
        return $this->belongsTo(SafeboxType::class);
    }

    public function getBalanceAttribute()
    {
        $transactions = Transaction::where('safebox_id', $this->id)->get();
        return $transactions->where('direction', 0)->sum('amount') - $transactions->where('direction', 1)->sum('amount');
    }
}
