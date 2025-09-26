<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                "user_name" => "Admin",
                "email"     => "admin@gmail.com",
                "password"  => "Ks82787294",
                'role'      => "Admin"
            ],
            [
                "user_name" => "User",
                "email"     => "user@gmail.com",
                "password"  => "Ks82787294",
                'role'      => "Tenant"
            ]
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
