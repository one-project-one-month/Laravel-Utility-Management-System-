<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = [
                "user_name" => "John Doe",
                "email"     => "johndoe@gmail.com",
                "password"  => Hash::make("Ks82787294"),
                "role"      => "Admin"
            ];

        $staff =  [
                "user_name" => "Alice Doe",
                "email"     => "alice244doe@gmail.com",
                "password"  => Hash::make("Ks82787294"),
                "role"      => "Admin"
        ];

        foreach ($admins as $admin) {
            User::create($admin);
        }

        $tenants = Tenant::select('name','email','id')->get();

        foreach ($tenants as $tenant) {
            User::create([
                "user_name" => $tenant->name,
                "email"     => $tenant->email,
                "password"  => Hash::make("Ks82787294"),
                "role"      => "Tenant",
                "tenant_id" => $tenant->id
            ]);
        }
    }
}
