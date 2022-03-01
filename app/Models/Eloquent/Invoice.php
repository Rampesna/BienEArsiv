<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    public function type()
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
