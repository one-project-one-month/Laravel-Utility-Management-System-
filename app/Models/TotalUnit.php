<?php

namespace App\Models;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Model;

class TotalUnit extends Model
{
    protected $fillable = [
            'bill_id',
            'electricity_units',
            'water_units'
    ];

    public function bill() {
        return $this->belongsTo(Bill::class);
    }
}
