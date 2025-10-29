<?php
namespace Tests\Feature;

beforeEach(function () {
      $this->api = "/api/v1/contract-types/";
});

describe('Dashboard',function()
{
    test('get_contract_type_lists',function()
    {
        $admin = adminUserCreate();
        contractTypeCreate();

        $this->actingAs($admin,'sanctum')
            ->getJson($this->api )
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content'=>[
                    'data',
                    'meta',
                    'links'
                ],
                'status',
            ]);
    });

    test('store_new_contract_type',function()
    {
        $admin = adminUserCreate();

        $this->actingAs($admin,'sanctum')
            ->postJson($this->api ,[
                'name' => '12 months',
                'duration' => 12,
                'price' => 9600000.00,
                'facilities' => ['aircon','Tv']
            ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });

    test('store_new_contract_type_validation_error',function()
    {
        $admin = adminUserCreate();

        $this->actingAs($admin,'sanctum')
            ->postJson($this->api ,[
                'name' => '',
                'duration' => '12',
                'price' => 9600000,
                'facilities' => 'invalid'
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });

    test('update_contract_type_information',function()
    {
        $admin = adminUserCreate();
        $contractType =contractTypeCreate();

        $this->actingAs($admin,'sanctum')
            ->putJson($this->api.$contractType->id,[
                    'name'       => '8 months',
                    'duration'   => 8,
                    'price'      => 6800000.00,
                    'facilities' =>  ['Smart Tv','Washing Machine','Air-con'],
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });


    test('update_contract_type_validation_error',function()
    {
        $admin = adminUserCreate();
        $contractType =contractTypeCreate();

        $this->actingAs($admin,'sanctum')
            ->putJson($this->api.$contractType->id,[
                    'name'       => 8,
                    'duration'   => '',
                    'price'      => 6800000,
                    'facilities' =>  'invalid',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });

    test('show_contract_type_information',function()
    {
        $admin = adminUserCreate();
        $contractType =contractTypeCreate();

        $this->actingAs($admin,'sanctum')
            ->getJson($this->api.$contractType->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content',
                'status'
            ]);
    });


    test('returns_404_if_contract_type_not_found',function()
    {
        $admin = adminUserCreate();
        $contractType =contractTypeCreate();

        $this->actingAs($admin,'sanctum')
            ->getJson($this->api.'900')
            ->assertStatus(404)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });

    test('unauthenticated_user_cannot_access_contract_types_api',function()
    {
        $this->getJson($this->api )
            ->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    });

    test('non_tenant_cannot_access_contract_types_api',function()
    {
        $tenant = tenantUserCreate();

        $this->actingAs($tenant,'sanctum')
            ->getJson($this->api )
            ->assertStatus(403)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });
});

