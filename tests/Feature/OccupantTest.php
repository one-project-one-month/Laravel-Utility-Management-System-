<?php

beforeEach(function () {
    $this->api = '/api/v1/occupants/';
});


describe('Dashboard',function()
{
    test('get_occupants_lists',function ()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);
        occupantCreate($tenant);

        $this->actingAs($admin,'sanctum')
             ->getJson($this->api)
             ->assertStatus(200)
             ->assertJsonStructure([
                'success',
                'message',
                'content' => [
                    'data',
                    'meta',
                    'links'
                ],
                'status'
                ]);
    });

    test('store_new_occupant',function ()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);

        $this->actingAs($admin,'sanctum')
             ->postJson($this->api,[
                'name' => 'Zaw Zaw',
                'nrc'  => '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999),
                'relationshipToTenant' => "Child",
                'tenantId'  => $tenant->id
             ])
             ->assertStatus(201)
             ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
                ]);
    });

    test('store_new_occupant_validation_error',function ()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);

        $this->actingAs($admin,'sanctum')
             ->postJson($this->api,[
                'name' => 'Zaw Zaw',
                'nrc'  => '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999),
                'relationshipToTenant' => "child",
                'tenantId'  => $tenant->id
             ])
             ->assertStatus(422)
             ->assertJsonStructure([
                'success',
                'message',
                'status'
                ]);
    });

    test('update_occupant_information',function ()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $occupant = occupantCreate($tenant);

        $this->actingAs($admin,'sanctum')
             ->putJson($this->api.$occupant->id,[
                'name' => 'Zaw Zaw',
                'nrc'  => '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999),
                'relationshipToTenant' => "Child",
             ])
             ->assertStatus(200)
             ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
                ]);
    });

    test('update_occupant_information_validation_error',function ()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $occupant = occupantCreate($tenant);

        $this->actingAs($admin,'sanctum')
             ->putJson($this->api.$occupant->id,[
                'name' => null,
                'nrc'  => '12/PZT(N)' . fake()->unique()->numberBetween(100000, 999999),
                'relationshipToTenant' => "Child",
             ])
             ->assertStatus(422)
             ->assertJsonStructure([
                'success',
                'message',
                'status'
                ]);
    });

    test('show_occupant_information',function ()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $occupant = occupantCreate($tenant);

        $this->actingAs($admin,'sanctum')
             ->getJson($this->api.$occupant->id)
             ->assertStatus(200)
             ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
                ]);
    });

    test('returns_404_if_occupant_not_found',function ()
    {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $occupant = occupantCreate($tenant);

        $this->actingAs($admin,'sanctum')
             ->getJson($this->api.'100')
             ->assertStatus(404)
             ->assertJsonStructure([
                'success',
                'message',
                'status'
                ]);
    });

    test('unauthenticated_user_cannot_access_occupants_api',function()
    {
            $this->getJson($this->api)
                ->assertStatus(401)
                ->assertJsonStructure(
                [
                    'message',
                ]);
    });

    test('non_tenant_cannot_access_occupants_api', function ()
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
