<?php

namespace Database\Seeders;

use App\Models\User;
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
        $admins = [
            [
                "user_name" => "John Doe",
                "email"     => "johndoe@gmail.com",
                "password"  => Hash::make("Ks82787294"),
                "role"      => "Admin"
            ],
            [
                "user_name" => "Alice Doe",
                "email"     => "alicedoe@gmail.com",
                "password"  => Hash::make("Ks82787294"),
                "role"      => "Admin"
            ]
        ];

        foreach ($admins as $admin) {
            User::create($admin);
        }

        $tenants = DB::table('tenants')
            ->select('id as tenant_id')
            ->selectRaw('names[1] as first_tenant_name') // get the first name from names array
            ->selectRaw('emails[1] as first_tenant_email') // get the first email from emails array
            ->get();


        foreach ($tenants as $tenant) {
            User::create([
                "user_name" => $tenant->first_tenant_name,
                "email"     => $tenant->first_tenant_email,
                "password"  => Hash::make("Ks82787294"),
                "role"      => "Tenant",
                "tenant_id" => $tenant->tenant_id
            ]);
        }
    }
}
