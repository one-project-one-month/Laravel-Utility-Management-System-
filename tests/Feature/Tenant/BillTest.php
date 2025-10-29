<?php

beforeEach(function(){
    $this->tenantApi = "/api/v1/tenants";
});

describe('Tenant', function(){
    test('get_latest_bill', function(){
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $userTenant = tenantUserCreate($tenant);
        billCreate($room,$tenant);
        $this
        ->actingAs($userTenant, 'sanctum')
        ->getJson("$this->tenantApi/$tenant->id/bills/latest")
        ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
        ])
        ->assertStatus(200);
    });
    test('get_bill_history', function(){
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $userTenant = tenantUserCreate($tenant);
        billCreate($room,$tenant);
        billCreate($room,$tenant);
        billCreate($room,$tenant);
        $this
        ->actingAs($userTenant, 'sanctum')
        ->getJson("$this->tenantApi/$tenant->id/bills/history")
        ->assertJsonStructure([
            'success',
            'message',
            'content',
            'status'
        ])
        ->assertStatus(200);
    });
    test('get_bill_history_returns_empty_list_for_new_tenant', function(){
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $userTenant = tenantUserCreate($tenant);
        $this->actingAs($userTenant, 'sanctum')
        ->getJson("$this->tenantApi/$tenant->id/bills/history")
        ->assertJsonStructure([
            'success',
            'message',
            'status'
        ])
        ->assertStatus(200);
    });
    test('user_cannot_get_another_tenants_latest_bill' ,function(){
        $room1 = roomCreate();
        $room2 = roomCreate();
        $tenant1 = tenantCreate($room1);
        $tenant2 = tenantCreate($room2);
        $userTenant1 = tenantUserCreate($tenant1);
        $this->actingAs($userTenant1, 'sanctum')
        ->getJson("$this->tenantApi/$tenant2->id/bills/latest")
        ->assertJsonStructure([
            'success',
            'message',
            'status'
        ])
        ->assertStatus(401);
    });

});