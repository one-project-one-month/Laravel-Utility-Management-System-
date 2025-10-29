<?php

describe('Tenant', function(){
    beforeEach(function(){
        $room = roomCreate();
        $this->tenant = tenantCreate($room);
        $this->tenantUser = tenantUserCreate($this->tenant);
        $this->tenantApi = "/api/v1/tenants/{$this->tenant->id}/contracts";
    });
    test('test_tenant_can_get_contracts', function(){
        
        $contractType = contractTypeCreate();
        contractCreate($contractType,$this->tenant);
        contractCreate($contractType,$this->tenant);
        contractCreate($contractType,$this->tenant);
        $this->actingAs($this->tenantUser, 'sanctum')
        ->getJson($this->tenantApi)
        ->assertJsonStructure([
            'success',
            'content'=> [
                    'data',
                    'meta',
                    'links'
                ],
            'status'
        ])
        ->assertStatus(200)
        ->assertJsonCount(3,'content.data');
    });
    test('test_unauthenticated_user_cannot_get_contracts', function(){
        
        $contractType = contractTypeCreate();
        contractCreate($contractType,$this->tenant);
        $this->getJson($this->tenantApi)
        ->assertJsonStructure([
            'message'
        ])
        ->assertStatus(401);
    });
    test('test_user_from_another_tenant_cannot_get_contracts', function(){
        $room2 = roomCreate();
        $tenant2 = tenantCreate($room2);
        $this->actingAs($this->tenantUser, 'sanctum')
        ->getJson("/api/v1/tenants/$tenant2->id/contracts")
        ->assertJsonStructure([
            'success',
            'message',
            'status'
        ])
        ->assertStatus(401);
    });

});