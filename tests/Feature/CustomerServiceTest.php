<?php

beforeEach(function () {
    $this->api = '/api/v1/customer-services';
});

describe('Dashboard', function () {
    test('get_customer_services', function () {

        $admin = adminUserCreate();
        $room = roomCreate();

        customerServiceCreate($room);
        customerServiceCreate($room);

        $this->actingAs($admin, 'sanctum')
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
            ])
            ->assertJsonCount(2, 'content.data');
    });

    test('update_customer_services', function () {
        $admin = adminUserCreate();
        $room = roomCreate();
        $cusService = customerServiceCreate($room);
        $this->actingAs($admin, 'sanctum')
            ->putJson($this->api . '/'  . $cusService->id, [
                "roomId"        =>  $room->id,
                "category"       => 'Complain',
                "description"    => 'Disfacilis fuga earum nobis optio commodi.',
                "status"         => 'Ongoing',
                "priorityLevel" => 'Low',
                "issuedDate"    => '2025-01-01'
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
        $this->assertDatabaseHas('customer_services', [
            'id'               => $cusService->id,
            "category"         => 'Complain',
            'description'      => 'Disfacilis fuga earum nobis optio commodi.',
            'status'           => 'Ongoing',
            'priority_level'   => 'Low'
        ]);
    });
    test('update_customer_services_validation_error', function () {
        $admin = adminUserCreate();
        $room = roomCreate();
        $cusService = customerServiceCreate($room);
        $this->actingAs($admin, 'sanctum')
            ->putJson($this->api . '/'  . $cusService->id, [
                "roomId"        =>  '',
                "category"       => 'Complain',
                "description"    => 'Disfacilis fuga earum nobis optio commodi.',
                "status"         => 'Ongoing',
                "priorityLevel" => 'Low',
                "issuedDate"    => '2025-01-01'
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });

    test('show_customer_services', function () {
        $admin = adminUserCreate();
        $room = roomCreate();
        $cusService = customerServiceCreate($room);

        $this->actingAs($admin, 'sanctum')
            ->getJson($this->api . '/' . $cusService->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });

    test('unauthenticated_user_cannot_access_customer_services_api', function () {
        $this->getJson($this->api)
            ->assertStatus(401)
            ->assertJsonStructure(
                [
                    'message',
                ]
            );
    });
    test('non_admin_cannot_access_customer_services_api', function(){
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $userTenant = tenantUserCreate($tenant);

        $this->actingAs($userTenant, 'sanctum')
        ->getJson($this->api)
        ->assertStatus(403)
        ->assertJsonStructure([
            'success',
            'message',
            'status'
        ]);
    });
    test('returns_404_if_customer_services_not_found', function(){
        $admin = adminUserCreate();
        $room = roomCreate();
        // customerServiceCreate($room);
        $this
        ->actingAs($admin, 'sanctum')
        ->getJson($this->api . '/' . 2)
        ->assertStatus(404)
        ->assertJsonStructure([
            'success',
            'message',
            'status'
        ]);

    });
});
