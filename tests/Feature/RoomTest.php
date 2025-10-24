<?php


beforeEach(function () {
    $this->api = '/api/v1/rooms/';
});

describe('Dashboard', function ()
{
    test('get_room_lists',function()
    {
        $admin = adminUserCreate();
        roomCreate();

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

    test('update_room_information',function()
    {
        $admin = adminUserCreate();
        $room  = roomCreate();

        $this->actingAs($admin,'sanctum')
            ->putJson($this->api.$room->id,[
                'roomNo' => 102,
                'floor' => 1,
                'dimension' => "500sqft",
                'noOfBedRoom' => '3',
                'status' => 'Rented',
                'sellingPrice' => 80000000.00,
                'maxNoOfPeople' => 3,
                'description' => "hellOWorld"
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });


    test('update_room_information_validation_error',function()
    {
        $admin = adminUserCreate();
        $room  = roomCreate();

        $this->actingAs($admin,'sanctum')
            ->putJson($this->api.$room->id,[
                'roomNo' => '2214a',
                'floor' => 1,
                'dimension' => "500sqft",
                'noOfBedRoom' => '3',
                'status' => 'Rented',
                'sellingPrice' => 80000000.00,
                'maxNoOfPeople' => 3,
                'description' => "hellOWorld"
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });


    test('show_room_information',function()
    {
        $admin = adminUserCreate();
        $room  = roomCreate();

        $this->actingAs($admin,'sanctum')
            ->getJson($this->api.$room->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });


    test('returns_404_if_room_not_found',function()
    {
        $admin = adminUserCreate();

        $this->actingAs($admin,'sanctum')
            ->getJson($this->api.fake()->uuid())
            ->assertStatus(404)
            ->assertJsonStructure(
            [
                'success',
                'message',
                'status'
            ]);

    });

    test('unauthenticated_user_cannot_access_rooms_api',function()
    {
            $this->getJson($this->api)
                ->assertStatus(401)
                ->assertJsonStructure(
                [
                    'message',
                ]);
    });

    test('non_tenant_cannot_access_rooms_api', function ()
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
