<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Nette\Utils\Random;
use App\Models\Occupant;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use App\Enums\RelationshipToTenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OccupantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenantIds = Tenant::pluck('id');

        $relationshipToTenant = RelationshipToTenant::cases();

        foreach($tenantIds as $tenantId) {
            for( $i= 0 ; $i < 4 ; $i++) {

                    Occupant::create([
                    'name' => fake()->name(),
                    'nrc'  =>  '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999),
                    'relationship_to_tenant' => Arr::random($relationshipToTenant)->value,
                    'tenant_id' => $tenantId
            ]);
            }
        }
    }
}
