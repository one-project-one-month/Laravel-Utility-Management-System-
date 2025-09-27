<?php

namespace App\Models;

use App\Models\Tenant;
use App\Models\Contract;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
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

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
