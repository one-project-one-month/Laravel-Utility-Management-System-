<?php

use App\Models\Room;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Contract;
use Illuminate\Support\Str;
use App\Models\ContractType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}


function adminCreate() {
    $user = User::create([
        "user_name" => "John Doe",
        "email"     => "johndoe1@gmail.com",
        "password"  => Hash::make("Ks82787294"),
        "role"      => "Admin"
    ]);

    return $user;
}

function staffCreate() {
    $user = User::create([
        "user_name" => "John Staff",
        "email"     => "johnstaff@gmail.com",
        "password"  => Hash::make("Ks82787294"),
        "role"      => "Staff"
    ]);

    return $user;
}

function tenantCreate() {
    $user = User::create([
        "user_name" => "John Doe",
        "email"     => "johndoe21@gmail.com",
        "password"  => Hash::make("Ks82787294"),
        "role"      => "Tenant"
    ]);

    return $user;
}

function tenant1Create($room) {
    $tenant = Tenant::create([
                'room_id'       => $room->id,
                'name'         => fake()->name(),
                'email'        => fake()->unique()->safeEmail(),
                'nrc'          => '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999),
                'phone_no'     => '09' . fake()->numerify('#########'),
                'emergency_no' => '09' . fake()->numerify('#########'),
    ]);

    return $tenant;
}

function roomCreate() {
    $room =      Room::create([
                'id'               => Str::uuid()->toString(),
                'room_no'          => 100,
                'floor'            => 1,
                'dimension'        => rand(200, 500) . ' sqft',
                'no_of_bed_room'   => rand(1, 4),
                'status'           => "Available",
                'selling_price'    => rand(5000000, 20000000),
                'max_no_of_people' => rand(1, 6),
                'description'      => 'This is a description for Room ' . 100
            ]);
    return $room;
}


function contractTypeCreate() {
    $contractType = ContractType::create([
                'name'       => '6 months',
                'duration'   => 6,
                'price'      => 800000.00,
                'facilities' =>  DB::raw("ARRAY['Smart Tv','Washing Machine','Air-con']"),
    ]);

    return $contractType;
}


function contractCreate($contractType,$tenant) {
    $contract = Contract::create([
        'contract_type_id' => $contractType->id,
        'room_id'   => $tenant->room_id,
        'tenant_id' => $tenant->id,
        'created_date' => '2020-01-01',
        'expiry_date' => '2022-01-01'
    ]);

    return $contract;
}

