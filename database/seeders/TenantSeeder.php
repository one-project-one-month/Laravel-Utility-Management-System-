<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::whereIn('status', ['Rented', 'Purchased'])->get(); // Corrected 'Rentend' to 'Rented'

        foreach ($rooms as $room) {
            $names = [];
            $emails = [];
            $nrcs = [];
            $phone_nos = [];
            $emergency_nos = [];

            $numberOfTenants = $room->max_no_of_people;

            for ($i = 0; $i < $numberOfTenants; $i++) {
                $names[] = Str::ascii(fake()->name()); // Fix name that O'Liver to Oliver
                $emails[] = fake()->unique()->safeEmail();
                $nrcs[] = '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999);
                $phone_nos[] = '09' . fake()->numerify('#########');
                $emergency_nos[] = '09' . fake()->numerify('#########');
            }

            Tenant::create([
                'room_id' => $room->id,
                'names' => DB::raw("ARRAY['" . implode("','", $names) . "']"),
                'emails' => DB::raw("ARRAY['" . implode("','", $emails) . "']"),
                'nrcs' => DB::raw("ARRAY['" . implode("','", $nrcs) . "']"),
                'phone_nos' => DB::raw("ARRAY['" . implode("','", $phone_nos) . "']"),
                'emergency_nos' => DB::raw("ARRAY['" . implode("','", $emergency_nos) . "']"),
            ]);
        }
    }
}
