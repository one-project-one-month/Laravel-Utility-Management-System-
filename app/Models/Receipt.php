<?php

namespace App\Models;

use App\Models\Invoice;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'invoice_id',
        'payment_method',
        'paid_date'
    ];
    protected $casts = [
        'payment_method' => PaymentMethod::class,
    ];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }
}
