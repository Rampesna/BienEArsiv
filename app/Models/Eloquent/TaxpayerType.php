<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxpayerType extends Model
{
    use HasFactory, SoftDeletes;

    public function customers()
    {
        return $this->hasMany(Customer::class, 'taxpayer_type_id', 'id');
    }
}
