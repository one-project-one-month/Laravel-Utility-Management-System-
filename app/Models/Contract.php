<?php

namespace App\Models;

use App\Models\ContractType;
use App\Models\CustomerService;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
            'contract_type_id',
            'tenant_id',
            'expiry_date'
    ];

    public function contractTypes() {
        return $this->belongsTo(ContractType::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }

     public function customerServices() {
        return $this->hasMany(CustomerService::class);
    }
}
