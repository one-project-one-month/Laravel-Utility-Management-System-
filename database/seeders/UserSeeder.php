<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                "user_name" => "Admin",
                "email" => "admin@gmail.com",
                "password" => Hash::make("Ks82787294"),
                'role' => "Admin"
            ],
            [
                "user_name" => "User",
                "email" => "user@gmail.com",
                "password" => Hash::make("Ks82787294"),
                'role' => "Tenant"
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
