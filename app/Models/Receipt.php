<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'invoice_id',
        'payment-method',
        'paid_date'
    ];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }
}
