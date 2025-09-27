<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Tenant;
use App\Models\Contract;
use Illuminate\Log\Logger;
use App\Models\ContractType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contractTypeId = ContractType::pluck('id');
        $rooms = Room::whereIn('status',['Rentend','Purchased'])->pluck('id');

        $tenants = [];
        foreach($rooms as $room) {
            $tenants[] = Tenant::where('room_id',$room)->get();
        }

        foreach( $tenants as $tenant) {
            foreach($tenant as $item) {
                    $data = Contract::create([
                            'contract_type_id' => $contractTypeId->random(),
                            'tenant_id'          => $item['id'],
                            'room_id'          => $item['room_id'],
                            'expiry_date'      => fake()->dateTimeBetween('2020-01-01', 'now')
                    ]);
            }
        }
    }
}

