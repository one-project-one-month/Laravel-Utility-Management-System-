<?php

beforeEach(function() {
    $this->api = "/api/v1/tenants/";
});

describe("Dashboard",function()
{
    test('get_tenant_list',function()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);

        $this->actingAs($admin,'sanctum')
             ->getJson($this->api)
             ->assertStatus(200)
             ->assertJsonStructure([
                'success',
                'message',
                'content'=> [
                    'data',
                    'meta',
                    'links'
                ],
                'status'
            ]);
    });

    test('store_new_tenant',function()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);

        $this->actingAs($admin,'sanctum')
             ->postJson($this->api,[
                    'roomId' => $room->id,
                    'name' => "Maung Maung",
                    'nrc' => '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999),
                    'email' => 'maungmaung@gmail.com',
                    'phNumber' => '09892929242',
                    'emergencyNo' => '0999042828'
             ])
             ->assertStatus(201)
             ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });

    test('store_new_tenant_validation_error',function()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);

        $this->actingAs($admin,'sanctum')
             ->postJson($this->api,[
                    'roomId' => 5281,
                    'name' => "MaungMaung",
                    'nrc' => '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999),
                    'email' => 'maungmaung@gmail.com',
                    'phNumber' => '09892929242',
                    'emergencyNo' => '0999042828'
             ])
             ->assertStatus(422)
             ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });

    test('update_tenant_information',function()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);

        $this->actingAs($admin,'sanctum')
             ->putJson($this->api.$tenant->id,[
                    'roomId' => $tenant->room_id,
                    'name' => "MaungMaung",
                    'nrc' => '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999),
                    'email' => 'maungmaung@gmail.com',
                    'phNumber' => '09892929242',
                    'emergencyNo' => '0999042828'
             ])
             ->assertStatus(200)
             ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });

    test('update_tenant_information_validation_error',function()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);

        $this->actingAs($admin,'sanctum')
             ->putJson($this->api.$tenant->id,[
                    'roomId' => $tenant->room_id,
                    'name' => "MaungMaung",
                    'nrc' => '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999),
                    'email' => '',
                    'phNumber' => '09892929242',
                    'emergencyNo' => '0999042828'
             ])
             ->assertStatus(422)
             ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });

    test('show_tenant_information',function()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);

        $this->actingAs($admin,'sanctum')
             ->getJson($this->api.$tenant->id)
             ->assertStatus(200)
             ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });

    test('returns_404_if_tenant_not_found',function ()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);

        $this->actingAs($admin,'sanctum')
             ->getJson($this->api.'100')
             ->assertStatus(404)
             ->assertJsonStructure([
                'success',
                'message',
                'status'
                ]);
    });

    test('unauthenticated_user_cannot_access_tenants_api',function()
    {
            $this->getJson($this->api)
                ->assertStatus(401)
                ->assertJsonStructure(
                [
                    'message',
                ]);
    });

    test('non_tenant_cannot_access_tenants_api', function ()
    {
        $tenant = tenantUserCreate();

        $this->actingAs($tenant, 'sanctum')
            ->getJson($this->api)
            ->assertStatus(403)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });
});
