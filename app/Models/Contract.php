<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\ContractType;
use App\Models\CustomerService;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
   public $incrementing = false;
   protected $keyType = 'string';

    protected $fillable = [
            'room_id',
            'contract_type_id',
            'tenant_id',
            'created_date',
            'expiry_date'
    ];

     protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function contractType() {
        return $this->belongsTo(ContractType::class);
    }
    public function tenant(){
        return $this->belongsTo(Tenant::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }

     public function customerServices() {
        return $this->hasMany(CustomerService::class);
    }
}
