<?php

namespace App\Models;

use App\Models\Bill;
use App\Models\Receipt;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_no',
        'bill_id',
        'status',
        'receipt_sent'
    ];

    public function bill() {
        return $this->belongsTo(Bill::class);
    }

    public function receipt() {
        return $this->belongsTo(Receipt::class);
    }
}
