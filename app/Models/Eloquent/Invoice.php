<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function type()
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function status()
    {
        return $this->belongsTo(InvoiceStatus::class);
    }

    public function products()
    {
        return $this->belongsTo(InvoiceProduct::class);
    }

    public function currencyName()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function vatDiscount()
    {
        return $this->belongsTo(VatDiscount::class, 'vat_discount_id', 'id');
    }
}
