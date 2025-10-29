<?php 

use Illuminate\Support\Carbon;

describe('Tenant', function(){

    beforeEach(function(){
        $this->room = roomCreate();
        $this->tenant = tenantCreate($this->room);
        $this->tenantUser = tenantUserCreate($this->tenant);
        $this->api = "api/v1/tenants/{$this->tenant->id}/invoices/";

    });
    test('get_latest_invoice', function()
    {

        $bill = billCreate($this->room, $this->tenant);
        totalUnitCreate($bill);
        invoiceCreate($bill);
        $this->actingAs($this->tenantUser, 'sanctum')
        ->getJson($this->api . 'latest')
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'content',
            'status'
        ]);

    });
    
    test('get_invoice_history', function()
    {

        $bill = billCreate($this->room, $this->tenant);
        totalUnitCreate($bill);
        Carbon::setTestNow(Carbon::parse('2025-10-29 10:00:00'));
        //Past Invoice        
        $pastInvoice = invoiceCreate($bill);
        $pastInvoice->created_at = Carbon::now()->subMonth(2);
        $pastInvoice->save();
        $this->actingAs($this->tenantUser, 'sanctum')
        ->getJson($this->api . 'history')
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
        ])
        ->assertJsonCount(1, 'content.data');
        Carbon::setTestNow();

    });

    test('test_get_invoice_history_does_not_return_current_month_invoices', function()
    {

       $bill = billCreate($this->room, $this->tenant);
        totalUnitCreate($bill);
        Carbon::setTestNow(Carbon::parse('2025-10-29 10:00:00'));
        //Past Invoice        
        $pastInvoice = invoiceCreate($bill);
        $pastInvoice->created_at = Carbon::now()->subMonth(2);
        $pastInvoice->save();
        invoiceCreate($bill);
        $this->actingAs($this->tenantUser, 'sanctum')
        ->getJson($this->api . 'history')
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'content'=> [
                    'data',
                    'meta',
                    'links'
                ],
            'message',
            'status'
            ]);
        Carbon::setTestNow();

    });

    test('test_get_latest_invoice_returns_the_most_recent_one', function()
    {

        $bill = billCreate($this->room, $this->tenant);
        totalUnitCreate($bill);
        Carbon::setTestNow(Carbon::parse('2025-10-29 10:00:00'));
        //Past Invoice        
        $pastInvoice = invoiceCreate($bill);
        $pastInvoice->created_at = Carbon::now()->subMonth(2);
        $pastInvoice->save();
        $currentInvoice = invoiceCreate($bill);
        $this->actingAs($this->tenantUser, 'sanctum')
        ->getJson($this->api . 'latest')
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'content',
            'message',
            'status'
        ])
        ->assertJsonPath('content.id' , $currentInvoice->id);
        Carbon::setTestNow();

    });

    test('test_user_from_another_tenant_cannot_get_invoices', function()
    {

        $room = roomCreate();
        $tenant2 = tenantCreate($room);
        $this->actingAs($this->tenantUser, 'sanctum')
        ->getJson("api/v1/tenants/{$tenant2->id}/invoices/latest")
        ->assertJsonStructure([
            'message',
            'status'
        ])
        ->assertStatus(401);

    });
});