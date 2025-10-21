<?php

use App\Models\ContractType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\ContractTypeSeeder;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = adminCreate();
    $this->seed(ContractTypeSeeder::class);
});

test('show contract type lists', function () {
    $response = $this->actingAs($this->admin)
        ->getJson(route('contract-types.index'));

    $response
        ->assertStatus(200)
        ->assertJsonStructure(
            [
                'message',
                'success',
                'content' => [
                    'data',
                    'meta',
                    'links'
                ],
                'status'
            ]
        );
});

test('show a contract type', function () {
    $this->contractType = ContractType::first();

    $response = $this->actingAs($this->admin)
        ->getJson(route('contract-types.show', $this->contractType->id));

    $response
        ->assertStatus(200)
        ->assertJsonStructure(
            [
                'message',
                'success',
                'content',
                'status'
            ]
        );
});

test('store a new contract type', function () {
    $response = $this->actingAs($this->admin)
        ->postJson(
            route('contract-types.store'),
            [
                "name" => "12 month",
                "duration" => 12,
                "price" => 1000000,
                "facilities" => [
                    "Smart Tv",
                    "Washing Machine",
                    "Air-con"
                ]
            ]
        );

    $response->assertStatus(201);
});

test('update an existing contract type', function () {
    $this->contractType = ContractType::first();

    $response = $this->actingAs($this->admin)
        ->postJson(
            route('contract-types.store', $this->contractType),
            [
                "name" => "18 month",
                "duration" => 18,
                "price" => 800000,
                "facilities" => [
                    "Smart Tv",
                    "Washing Machine",
                    "Refrigerator",
                    "Message Chair"
                ]
            ]
        );

    $response->assertStatus(201);
});
