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
});