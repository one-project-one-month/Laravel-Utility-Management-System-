<?php

namespace App\Models;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;

class Occupant extends Model
{
    protected $fillable = [
        'name',
        'nrc',
        'tenant_id'
    ];

    public function tenants() {
        return $this->belongsTo(Tenant::class);
    }
}
