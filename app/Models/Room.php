<?php

namespace App\Models;

use App\Models\Tenant;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_no',
        'floor',
        'dimension',
        'no_of_bed_room',
        'status',
        'selling_price',
        'max_no_people',
        'description'
    ];

    public function tenant() {
        return $this->belongsTo(Tenant::class);
    }

    public function contracts () {
        return $this->hasMany(Contract::class);
    }
}
