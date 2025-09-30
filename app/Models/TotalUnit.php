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

    public function generateTotalUnit($length){
        if ($length === 1) {
            return rand(0, 9); 
        } elseif ($length === 2) {
            return rand(10, 99); 
        } elseif ($length === 3) {
            return rand(100, 999); 
        } else {
            return rand(0, 999);
        }
    }
}
