<?php

namespace App\Models;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Model;

class ContractType extends Model
{
    protected $fillable = [
        'name',
        'duration',
        'price',
        'facilities'
    ];

    public function contracts() {
        return $this->hasMany(Contract::class);
    }

    // protected $casts = [
    //     'facilities' => 'array',
    // ];
}
