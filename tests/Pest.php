<?php

use App\Models\User;
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
        "email"     => "johndoe@gmail.com",
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


function contractTypeCreate() {
    $contractType = ContractType::create([
                'name'       => '6 months',
                'duration'   => 6,
                'price'      => 800000.00,
                'facilities' =>  DB::raw("ARRAY['Smart Tv','Washing Machine','Air-con']"),
    ]);

    return $contractType;
}
