<?php

namespace App\Models;

use App\Models\Room;
use App\Models\User;
use App\Models\Occupant;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'room_id',
        'name',
        'email',
        'nrc',
        'phone_no',
        'emergency_no',
    ];

    public function user() {
        return $this->hasOne(User::class);
    }

    public function occupants() {
        return $this->hasMany(Occupant::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function bills() {
        return $this->hasMany(Bill::class);
    }

    public function contracts() {
        return $this->hasMany(Contract::class);
    }
}
