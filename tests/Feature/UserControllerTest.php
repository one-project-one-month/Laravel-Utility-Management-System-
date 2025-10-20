<?php

namespace Tests\Feature;

// use Tests\TestCase;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Hash;

test('get_user_lists',function () {
    $user = adminCreate();

    $this->actingAs($user,'web')
         ->getJson('/api/v1/users')
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
    $user = adminCreate();

    $this->actingAs($user,'web')
         ->postJson('/api/v1/users',
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

test('update_user_information',function()
{
    $user = adminCreate();

    $this->actingAs($user,'web')
        ->putJson('/api/v1/users/'.$user->id,
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

test('show_user_information',function()
{
    $user = adminCreate();

    $this->actingAs($user,'web')
         ->getJson('/api/v1/users/'.$user->id)
         ->assertStatus(200)
         ->assertJsonStructure(
          [
            'success',
            'message',
            'content',
            'status'
          ]);
});








