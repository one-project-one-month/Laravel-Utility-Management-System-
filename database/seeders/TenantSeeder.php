<?php

namespace Database\Seeders;

use App\Enums\RoomStatus;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::whereIn('status', [RoomStatus::Rented->value, RoomStatus::Purchased->value])->get();

        foreach ($rooms as $room) {
            Tenant::create([
                'room_id'       => $room->id,
                'name'         => fake()->name(),
                'email'        => fake()->unique()->safeEmail(),
                'nrc'          => '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999),
                'phone_no'     => '09' . fake()->numerify('#########'),
                'emergency_no' => '09' . fake()->numerify('#########'),
            ]);
        }
    }

    /**
     * Convert given php array to string and wrap with sql ARRAY datatype
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Database\Query\Expression|\Illuminate\Database\Query\Expression
     */
    private function wrapWithArrayDataType(array $data): \Illuminate\Database\Query\Expression|\Illuminate\Contracts\Database\Query\Expression
    {
        return DB::raw("ARRAY['" . implode("','", $data) . "']");
    }
}
