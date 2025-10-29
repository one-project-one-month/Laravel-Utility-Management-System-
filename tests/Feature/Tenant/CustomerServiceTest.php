<?php

beforeEach(function(){
    $this->tenantApi = "/api/v1/tenants";
});

describe('Tenant', function () {
    test('store_new_customer_services', function () {
        
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $userTenant = tenantUserCreate($tenant);
        
        $this->actingAs($userTenant, 'sanctum')
            ->postJson("$this->tenantApi/$tenant->id/customer-services/create", [
                "roomId"        =>  $room->id,
                "category"       => 'Complain',
                "description"    => 'Distinctio ipsa facilis fuga earum nobis optio commodi.',
                "status"         => 'Pending',
                "priorityLevel" => 'Low',
                "issuedDate"    => '2025-01-01'
            ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'content',
                'status',
            ]);
    });
    test('store_new_customer_services_validation_error', function () {
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $userTenant = tenantUserCreate($tenant);

        $this->actingAs($userTenant, 'sanctum')
            ->postJson("/api/v1/tenants/$tenant->id/customer-services/create", [
                "roomId"        =>  'hello',
                "category"       => 0001,
                "description"    => 'Distinctio ipsa facilis fuga earum nobis optio commodi.',
                "status"         => 'Pending',
                "priorityLevel" => 'Medium',
                "issuedDate"    => '2025-01-01'
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'status',
            ]);
    });
    test('guest_cannot_store_service_request', function(){
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $this->postJson("$this->tenantApi/$tenant->id/customer-services/create", [
                "roomId"        =>  $room->id,
                "category"       => 'Complain',
                "description"    => 'Distinctio ipsa facilis fuga earum nobis optio commodi.',
                "status"         => 'Pending',
                "priorityLevel" => 'Low',
                "issuedDate"    => '2025-01-01'
        ])
        ->assertStatus(401);
    });
    test('get_customer_services_history', function(){
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $tenantUser = tenantUserCreate($tenant);
        customerServiceCreate($room);
        $this->actingAs($tenantUser, 'sanctum')
        ->getJson("$this->tenantApi/$tenant->id/customer-services/history")
        ->assertJsonStructure([
            'success',
            'message',
            'content'=> [
                    'data',
                    'meta',
                    'links'
                ],
            'status'
        ])
        ->assertStatus(200)
        ->assertJsonCount(1, 'content.data');
    });
    test('guest_cannot_get_service_history',function(){
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $this
        ->getJson("$this->tenantApi/$tenant->id/customer-services/history")
        ->assertJsonStructure([
            'message',
        ])
        ->assertStatus(401);
    } );
});
