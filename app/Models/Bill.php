<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\TotalUnit;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'room_id',
        'rental_fee',
        'electricity_fee',
        'water_fee',
        'fine_fee',
        'service_fee',
        'ground_fee',
        'car_parking_fee',
        'wifi_fee',
        'total_amount',
        'due_date'
    ];

    public function totalUnit() {
        return $this->belongsTo(TotalUnit::class);
    }

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }
}
