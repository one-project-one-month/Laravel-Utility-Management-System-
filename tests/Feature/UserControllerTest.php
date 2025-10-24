<?php

beforeEach(function(){
    $this->api = '/api/v1/users/';
});

describe('Dashboard',function()
{
    test('get_user_lists',function ()
    {
        $user = adminUserCreate();

        $this->actingAs($user,'sanctum')
            ->getJson($this->api)
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
            ]);
    });

    test('store_new_user',function ()
    {
        $user = adminUserCreate();

        $this->actingAs($user,'sanctum')
            ->postJson($this->api,
            [
                'userName' => 'ZawZaw',
                'email'    =>  'zawzaw@gmail.com',
                'password' =>   'ks92874282768',
                'role'     =>  'Admin'
            ])
            ->assertStatus(201)
            ->assertJsonStructure(
            [
            'message',
            'success',
            'content',
            'status'
            ]);
    });

    test('store_new_user_validation_error', function ()
    {
        $user = adminUserCreate();

        $this->actingAs($user, 'sanctum')
            ->postJson($this->api, [
                'userName' => '',
                'email' => 'invalid-email',
                'password' => '12345',
                'role' => 'UnknownRole',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'success',
                'status',
            ]);
    });


    test('update_user_information',function()
    {
        $user = adminUserCreate();

        $this->actingAs($user,'sanctum')
            ->putJson($this->api.$user->id,
            [
                'userName' => 'MaungMaung',
                'email'     =>  'johndoe@gmail.com',
                "role"      => "Admin",
                "isActive"  => 1
            ])
            ->assertStatus(200)
            ->assertJsonStructure(
            [
                'message',
                'success',
                'content',
                'status'
            ]);
    });

    test('update_user_information_validation_error',function()
    {
        $user = adminUserCreate();

        $this->actingAs($user,'sanctum')
            ->putJson($this->api.$user->id,
            [
                'userName' => '',
                'email'    => 'invalid-email',
                'role'     => 'invalid-role',
                "isActive"  => 'wa'
            ]
            )
            ->assertStatus(422)
            ->assertJsonStructure(
                [
                    'success',
                    'message',
                    'status'
                ]
                );
    });

    test('show_user_information',function()
    {
        $user = adminUserCreate();

        $this->actingAs($user,'sanctum')
            ->getJson($this->api.$user->id)
            ->assertStatus(200)
            ->assertJsonStructure(
            [
                'success',
                'message',
                'content',
                'status'
            ]);
    });

    test('returns_404_if_user_not_found',function()
    {
        $user = adminUserCreate();

        $this->actingAs($user,'sanctum')
            ->getJson($this->api.fake()->uuid())
            ->assertStatus(404)
            ->assertJsonStructure(
            [
                'success',
                'message',
                'status'
            ]);

    });

    test('unauthenticated_user_cannot_access_users_api',function()
    {

            $this->getJson($this->api)
                ->assertStatus(401)
                ->assertJsonStructure(
                [
                    'message',
                ]);
    });

    test('non_tenant_cannot_access_users_api', function ()
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

    test('can_filter_users_by_role', function ($role)
    {
        $admin = adminUserCreate();
        staffUserCreate();
        tenantUserCreate();

        $response = $this->actingAs($admin, 'sanctum')
                        ->getJson("/api/v1/users?role={$role}")
                        ->assertStatus(200)
                        ->json();

        $rolesInResponse = collect($response['content']['data'])
                        ->pluck('role')
                        ->unique();

        expect($rolesInResponse)->toContain($role);

        $otherRoles = collect(['Admin', 'Staff', 'Tenant'])
                    ->reject(fn($r) => $r === $role);

        foreach ($otherRoles as $otherRole)
        {
            expect($rolesInResponse)->not->toContain($otherRole);
        }

    })->with(['Admin', 'Staff', 'Tenant']);

});
