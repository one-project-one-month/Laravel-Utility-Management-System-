<?php

describe('Tenant', function(){
    test('get_contracts', function(){
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $contractType = contractTypeCreate();
        $tenantUser = tenantUserCreate($tenant);
        contractCreate($contractType,$tenant);
        $this->actingAs($tenantUser, 'sanctum')
        ->getJson("/api/v1/tenants/$tenant->id/contracts")
        ->assertJsonStructure([
            'success',
            'message',
            'content',
            'status'
        ])
        ->assertStatus(200)
        ->assertJsonCount(1, 'content');
    });

    test('tenant_cannot_access_another_tenants_contracts', function(){
        $room1 = roomCreate();
        $room2 = roomCreate();
        $tenant1 = tenantCreate($room1);
        $tenant2 = tenantCreate($room2);
        $tenantUser1 = tenantUserCreate($tenant1);
        $this->actingAs($tenantUser1, 'sanctum')
        ->getJson("/api/v1/tenants/$tenant2->id/contracts")
        ->assertJsonStructure([
            'success',
            'message',
            'status'
        ])
        ->assertStatus(401);
    });
    
});


    // test('get_contracts_returns_all_contracts_for_a_tenant', function(){
    //     $room = roomCreate();
    //     $tenant = tenantCreate($room);
    //     $contractType = contractTypeCreate();
    //     $tenantUser = tenantUserCreate($tenant);
    //     contractCreate($contractType,$tenant);
    //     $this->actingAs($tenantUser, 'sanctum')
    //     ->getJson("/api/v1/tenants/$tenant->id/contracts")
    //     ->assertJsonStructure([
    //         'success',
    //         'message',
    //         'content',
    //         'status'
    //     ])
    //     ->assertStatus(200)
    //     ->assertJsonCount(2, 'content');
    // });