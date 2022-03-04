<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceStatus extends Model
{
    use HasFactory, SoftDeletes;

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
