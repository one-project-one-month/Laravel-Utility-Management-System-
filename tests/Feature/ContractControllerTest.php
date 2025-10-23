<?php


describe('Dashboard', function ()
{
    test('get_contract_lists',function()
    {
        $admin = adminCreate();
        $room = roomCreate();
        $tenant = tenant1Create($room);
        $contractType = contractTypeCreate();
        contractCreate($contractType,$tenant);

        $this->actingAs($admin,'sanctum')
            ->getJson('/api/v1/contracts')
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

    test('store_new_contract',function()
    {
        $admin = adminCreate();
        $room = roomCreate();
        $tenant = tenant1Create($room);
        $contractType = contractTypeCreate();

        $this->actingAs($admin,'sanctum')
            ->postJson('/api/v1/contracts',[
            'roomNo'      => $room->id,
            'contractTypeId'  => $contractType->id,
            'tenantId'    => $tenant->id,
            'createdDate' => '2020-01-01',
            'expiryDate'  => '2021-01-01',
            ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });

    test('store_new_contract_validation_error',function()
    {
        $admin = adminCreate();
        $room = roomCreate();
        $tenant = tenant1Create($room);
        $contractType = contractTypeCreate();

        $this->actingAs($admin,'sanctum')
            ->postJson('/api/v1/contracts',[
            'roomNo'      => $room->id,
            'contractTypeId'  => '',
            'tenantId'    => $tenant->id,
            'createdDate' => '2020-01-01',
            'expiryDate'  => '2021-01-01',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });


    test('update_contract_information',function()
    {
        $admin = adminCreate();
        $room = roomCreate();
        $tenant = tenant1Create($room);
        $contractType = contractTypeCreate();
        $contract = contractCreate($contractType,$tenant);

        $this->actingAs($admin,'sanctum')
            ->putJson('/api/v1/contracts/'.$contract->id,[
            'roomNo'      => $room->id,
            'contractTypeId'  => $contractType->id,
            'tenantId'    => $tenant->id,
            'createdDate' => '2020-01-01',
            'expiryDate'  => '2021-01-01',
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });

    test('update_contract_information_validation_error',function()
    {
        $admin = adminCreate();
        $room = roomCreate();
        $tenant = tenant1Create($room);
        $contractType = contractTypeCreate();
        $contract = contractCreate($contractType,$tenant);

        $this->actingAs($admin,'sanctum')
            ->putJson('/api/v1/contracts/'.$contract->id,[
            'roomNo'      => $room->id,
            'contractTypeId'  => '',
            'tenantId'    => '',
            'createdDate' => '2020-01-01',
            'expiryDate'  => '2021-01-01',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });

    test('show_contract_information',function ()
    {
        $admin = adminCreate();
        $room = roomCreate();
        $tenant = tenant1Create($room);
        $contractType = contractTypeCreate();
        $contract = contractCreate($contractType,$tenant);

        $this->actingAs($admin,'sanctum')
            ->getJson('/api/v1/contracts/'.$contract->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });


    test('returns_404_if_contract_not_found',function()
    {
        $admin = adminCreate();

        $this->actingAs($admin,'sanctum')
            ->getJson('/api/v1/contracts/'.fake()->uuid())
            ->assertStatus(404)
            ->assertJsonStructure(
            [
                'success',
                'message',
                'status'
            ]);

    });

    test('unauthenticated_user_cannot_access_contracts_api',function()
    {

            $this->getJson('api/v1/contracts/')
                ->assertStatus(401)
                ->assertJsonStructure(
                [
                    'message',
                ]);
    });

    test('non_tenant_cannot_access_contracts_api', function ()
    {
        $tenant = tenantCreate();

        $this->actingAs($tenant, 'sanctum')
            ->getJson('/api/v1/contracts')
            ->assertStatus(403)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });
});
