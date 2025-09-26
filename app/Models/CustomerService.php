<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    protected $fillable = [
        'room_id',
        'category',
        'description',
        'status',
        'priority_level',
        'issued_date'
    ];

    public function rooms() {
        return $this->hasMany(Room::class);
    }
}
