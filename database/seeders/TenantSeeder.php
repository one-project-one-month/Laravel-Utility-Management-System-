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
            $names         = [];
            $emails        = [];
            $nrcs          = [];
            $phone_nos     = [];
            $emergency_nos = [];

            $numberOfTenants = $room->max_no_of_people;

            for ($i = 0; $i < $numberOfTenants; $i++) {
                $names[]         = Str::ascii(fake()->name()); // remove single quote from fake name, eg: O'Liver to Oliver
                $emails[]        = fake()->unique()->safeEmail();
                $nrcs[]          = '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999);
                $phone_nos[]     = '09' . fake()->numerify('#########');
                $emergency_nos[] = '09' . fake()->numerify('#########');
            }

            Tenant::create([
                'room_id'       => $room->id,
                'names'         => $this->wrapWithArrayDataType($names),
                'emails'        => $this->wrapWithArrayDataType($emails),
                'nrcs'          => $this->wrapWithArrayDataType($nrcs),
                'phone_nos'     => $this->wrapWithArrayDataType($phone_nos),
                'emergency_nos' => $this->wrapWithArrayDataType($emergency_nos),
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
        $escapedData = array_map(fn($item) => str_replace("'", "''", $item), $data);
        return DB::raw("ARRAY['" . implode("','", $escapedData) . "']");
    }
}
