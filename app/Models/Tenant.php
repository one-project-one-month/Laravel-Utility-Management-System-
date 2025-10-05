<?php

namespace App\Models;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'room_id',
        'names',
        'emails',
        'nrcs',
        'phone_nos',
        'emergency_nos',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function bills() {
        return $this->hasMany(Bill::class);
    }
}
