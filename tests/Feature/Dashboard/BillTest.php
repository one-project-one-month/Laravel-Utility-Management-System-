<?php

beforeEach(function() {
    $this->api = '/api/v1/bills/';
});

describe('Dashboard',function()
{
    test('get_bill_lists',function()
    {
        $admin = adminUserCreate();
        $room  = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room,$tenant);
        $totalUnit = totalUnitCreate($bill);

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

    test('show_bill_information',function()
    {
        $admin = adminUserCreate();
        $room  = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room,$tenant);
        $totalUnit = totalUnitCreate($bill);

        $this->actingAs($admin,'sanctum')
             ->getJson($this->api.$bill->id)
             ->assertStatus(200)
             ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
             ]);
    });

    test('returns_404_if_bill_not_found',function ()
    {
        $admin = adminUserCreate();
        $room  = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room,$tenant);
        $totalUnit = totalUnitCreate($bill);

        $this->actingAs($admin,'sanctum')
             ->getJson($this->api.'100')
             ->assertStatus(404)
             ->assertJsonStructure([
                'success',
                'message',
                'status'
                ]);
    });

    test('unauthenticated_user_cannot_access_bills_api',function()
    {
            $this->getJson($this->api)
                ->assertStatus(401)
                ->assertJsonStructure(
                [
                    'message',
                ]);
    });

    test('non_tenant_cannot_access_bills_api', function ()
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
