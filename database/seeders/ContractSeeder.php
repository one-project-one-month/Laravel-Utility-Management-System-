<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\Contract;
use App\Models\ContractType;
use Illuminate\Database\Seeder;
use App\Enums\RoomStatus;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contractTypeId = ContractType::pluck('id');

        $tenants = Tenant::select('tenants.*')
            ->join('rooms as rm', 'rm.id', '=', 'tenants.room_id')
            ->whereIn('rm.status', [RoomStatus::Rented->value, RoomStatus::Purchased->value])
            ->get();

        foreach($tenants as $tenant) {
            Contract::create([
                'contract_type_id' => $contractTypeId->random(),
                'tenant_id'        => $tenant->id,
                'room_id'          => $tenant->room_id,
                'created_date'    => fake()->dateTimeBetween('2020-01-01'),
                'expiry_date'      => fake()->dateTimeBetween('2020-01-01')
            ]);
        }
    }
}

