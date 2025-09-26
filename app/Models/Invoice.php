<?php

namespace App\Models;

use App\Models\Bill;
use App\Models\Receipt;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'bill_id',
        'status'
    ];

    public function bill() {
        return $this->belongsTo(Bill::class);
    }

    public function receipt() {
        return $this->belongsTo(Receipt::class);
    }
}
