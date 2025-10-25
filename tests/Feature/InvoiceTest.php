<?php

beforeEach(function()
{
    $this->api = '/api/v1/invoices/';
});

describe('Dashboard',function()
{
    test('get_invoice_lists',function()
    {
        $admin = adminUserCreate();
        $room  = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room,$tenant);
        $totalUnit = totalUnitCreate($bill);
        $invoice = invoiceCreate($bill);

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

    test('store_new_invoice',function()
    {
        $admin = adminUserCreate();
        $room  = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room,$tenant);

        $this->actingAs($admin,'sanctum')
             ->postJson($this->api,[
                    'billId'   => $bill->id,
                    'status'   => "Pending"
             ])
             ->assertStatus(201)
             ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
             ]);
    });

    test('store_new_invoice_validation_error',function()
    {
        $admin = adminUserCreate();
        $room  = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room,$tenant);

        $this->actingAs($admin,'sanctum')
             ->postJson($this->api,[
                    'billId'   => $bill->id,
                    'status'   => null
             ])
             ->assertStatus(422)
             ->assertJsonStructure([
                'success',
                'message',
                'status'
             ]);
    });

    test('update_invoice_information',function()
    {
        $admin = adminUserCreate();
        $room  = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room,$tenant);
              $totalUnit = totalUnitCreate($bill);
        $invoice = invoiceCreate($bill);

        $this->actingAs($admin,'sanctum')
             ->putJson($this->api.$invoice->id,[
                    'billId'   => $bill->id,
                    'status'   => "Pending"
             ])
             ->assertStatus(200)
             ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
             ]);
    });

    test('update_invoice_information_validation_error',function()
    {
        $admin = adminUserCreate();
        $room  = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room,$tenant);
        $totalUnit = totalUnitCreate($bill);
        $invoice = invoiceCreate($bill);

        $this->actingAs($admin,'sanctum')
             ->putJson($this->api.$invoice->id,[
                    'billId'   => $bill->id,
                    'status'   => 12,
             ])
             ->assertStatus(422)
             ->assertJsonStructure([
                'success',
                'message',
                'status'
             ]);
    });

    test('show_invoice_information',function()
    {
        $admin = adminUserCreate();
        $room  = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room,$tenant);
        $totalUnit = totalUnitCreate($bill);
        $invoice = invoiceCreate($bill);

        $this->actingAs($admin,'sanctum')
             ->getJson($this->api.$invoice->id)
             ->assertStatus(200)
             ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
             ]);
    });

    test('returns_404_if_invoice_not_found',function ()
    {
            $admin = adminUserCreate();
        $room  = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room,$tenant);
        $totalUnit = totalUnitCreate($bill);
        $invoice = invoiceCreate($bill);

        $this->actingAs($admin,'sanctum')
             ->getJson($this->api.'100')
             ->assertStatus(404)
             ->assertJsonStructure([
                'success',
                'message',
                'status'
                ]);
    });

    test('unauthenticated_user_cannot_access_invoices_api',function()
    {
            $this->getJson($this->api)
                ->assertStatus(401)
                ->assertJsonStructure(
                [
                    'message',
                ]);
    });

    test('non_tenant_cannot_access_invoices_api', function ()
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
