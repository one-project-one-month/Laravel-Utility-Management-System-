<?php

beforeEach(function () {
    $this->api = '/api/v1/receipts/';
});

describe('Dashboard', function () {
    test('get_receipt_list', function () {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room, $tenant);
        $invoice = invoiceCreate($bill);
        $receipt = receiptCreate($invoice);

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
            ]);
    });

    test('store_new_receipt', function () {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room, $tenant);
        $invoice = invoiceCreate($bill);

        $this->actingAs($admin, 'sanctum')
            ->postJson($this->api, [
                'invoiceId' => $invoice->id,
                'paymentMethod' => 'Cash',
                'paidDate' => '2025-06-05'
            ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });

    test('store_new_receipt_validation_error', function() {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room, $tenant);
        $invoice = invoiceCreate($bill);

        $this->actingAs($admin, 'sanctum')
            ->postJson($this->api, [
                'invoiceId' => $invoice->id,
                'paymentMethod' => null,
                'paidDate' => null
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });

    test('show_receipt_information', function () {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room, $tenant);
        $invoice = invoiceCreate($bill);
        $receipt = receiptCreate($invoice);

        $this->actingAs($admin, 'sanctum')
            ->getJson($this->api . $receipt->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });

    test('returns_404_if_receipt_not_found', function() {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room, $tenant);
        $invoice = invoiceCreate($bill);
        $receipt = receiptCreate($invoice);

        $this->actingAs($admin, 'sanctum')
            ->getJson($this->api . '200')
            ->assertStatus(404)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });

    test('update_receipt_information', function() {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room, $tenant);
        $invoice = invoiceCreate($bill);
        $receipt = receiptCreate($invoice);

        $this->actingAs($admin, 'sanctum')
            ->putJson($this->api . $receipt->id, [
                'invoiceId' => $invoice->id,
                'paymentMethod' => 'Cash',
                'paidDate' => '2025-06-05'
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });

    test('update_receipt_information_validation_error', function() {
        $admin = adminUserCreate();
        $room = roomCreate();
        $tenant = tenantCreate($room);
        $bill = billCreate($room, $tenant);
        $invoice = invoiceCreate($bill);
        $receipt = receiptCreate($invoice);

        $this->actingAs($admin, 'sanctum')
            ->putJson($this->api . $receipt->id, [
                'invoiceId' => $invoice->id,
                'paymentMethod' => 'cash',
                'paidDate' => '2025-06-05'
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });

    test('unauthenticated_user_cannot_access_receipt_api', function() {
        $this->getJson($this->api)
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    });

    test('non_tenant_cannot_access_receipt_api', function() {
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
