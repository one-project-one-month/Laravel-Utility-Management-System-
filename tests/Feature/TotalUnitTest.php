<?php

use App\Models\TotalUnit;

beforeEach(function () {
    $this->seed();
});

function getApi(string $uri = '')
{
    return '/api/v1/total-units' . (!empty($uri) ? '/' . rtrim($uri, '/') : '');
}

describe('Dashboard', function () {

    test('api cannot access unauthenticated user', function () {
        $this->getJson(getApi())
            ->assertUnauthorized();
    });

    test('api cannot access by non admin user', function () {
        $this->actingAs(tenantCreate(), 'sanctum')
            ->getJson(getApi())
            ->assertStatus(403)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });

    test('get total units', function () {
        $this->actingAs(adminCreate(), 'sanctum')
            ->getJson(getApi())
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content' => [
                    'data' => [
                        '*' => [
                            'id',
                            'billId',
                            'electricityUnits',
                            'waterUnits',
                            'tenantName',
                            'roomNo',
                            'totalAmount'
                        ]
                    ],
                    'meta' => [
                        'total',
                        'currentPage',
                        'lastPage',
                        'perPage',
                    ]
                ],
                'status'
            ]);
    });

    test('get specific total unit with id', function () {
        $max_id = TotalUnit::max('id');

        $this->actingAs(adminCreate(), 'sanctum')
            ->getJson(getApi($max_id))
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'content' => [
                    'id',
                    'billId',
                    'electricityUnits',
                    'waterUnits',
                    'tenantName',
                    'roomNo',
                    'totalAmount'
                ],
                'status'
            ]);
    });

    test('return 404 on total unit not found', function () {
        $max_id = TotalUnit::max('id');
        $nonExistingId = $max_id + 1;

        $this->actingAs(adminCreate(), 'sanctum')
            ->getJson(getApi($nonExistingId))
            ->assertStatus(404)
            ->assertJsonStructure([
                'success',
                'message',
                'status'
            ]);
    });
});
