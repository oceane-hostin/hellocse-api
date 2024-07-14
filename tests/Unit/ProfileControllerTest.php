<?php

namespace Tests\Unit;

use App\Models\Admin;
use Database\Seeders\ProfileSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{

    public function test_profile_index_public_route(): void
    {
        $response = $this->getJson('api/profiles');

        $response->assertStatus(Response::HTTP_OK);

        // public call for this route shouldn't return the status field
        $response->assertJson(fn (AssertableJson $json) =>
            $json
                ->first(fn (AssertableJson $jsonChild) =>
                $jsonChild
                    ->hasAll([
                        'id',
                        'firstname',
                        'lastname',
                        'image',
                        'created_at',
                        'updated_at',
                    ])
                    ->missing('status')
            )
        );
    }

    public function test_profile_index_admin_route() : void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ["*"]
        );

        $response = $this->getJson('api/profiles');

        $response->assertStatus(Response::HTTP_OK);

        // admin call must contain all field including status
        $response->assertJson(fn (AssertableJson $json) =>
        $json
            ->first(fn (AssertableJson $jsonChild) =>
            $jsonChild
                ->hasAll([
                    'id',
                    'firstname',
                    'lastname',
                    'image',
                    'status',
                    'created_at',
                    'updated_at',
                ])
            )
        );
    }

    public function test_profile_show_public_route() : void
    {
        // if data from seed not changed id 1 is published
        $response = $this->getJson('api/profile/1');
        $response->assertStatus(Response::HTTP_OK);
        // public call for this route shouldn't return the status field
        $response->assertJson(fn (AssertableJson $json) =>
        $json->hasAll([
                'id',
                'firstname',
                'lastname',
                'image',
                'created_at',
                'updated_at',
            ])
            ->missing('status')
        );

        // if data from seed not changed id 2 is unpublished
        $response = $this->getJson('api/profile/2');
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        // if data from seed not changed id 3 is awaiting
        $response = $this->getJson('api/profile/3');
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_profile_show_admin_route() : void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ["*"]
        );

        // if data from seed not changed id 2 is unpublished
        $response = $this->getJson('api/profile/2');
        $response->assertStatus(Response::HTTP_OK);

        // if data from seed not changed id 3 is awaiting
        $response = $this->getJson('api/profile/3');
        $response->assertStatus(Response::HTTP_OK);

        // admin call must contain all field including status
        $response->assertJson(fn (AssertableJson $json) =>
        $json->hasAll([
                'id',
                'firstname',
                'lastname',
                'image',
                'status',
                'created_at',
                'updated_at',
            ])
        );
    }

    public function test_profile_store_public_route() : void
    {
        // unauthenticated user cannot access this route
        $response = $this->postJson('api/profiles', [
            'firstname' => 'Test',
            'lastname' => 'Test',
            'image' => 'https://picsum.photos/200/300'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_profile_store_admin_route() : void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ["*"]
        );

        // Creation valid
        $response = $this->postJson('api/profiles', [
            'firstname' => 'Test',
            'lastname' => 'Test',
            'image' => 'https://picsum.photos/200/300'
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        // image field not required
        $response = $this->postJson('api/profiles', [
            'firstname' => 'Test',
            'lastname' => 'Test',
        ]);
        $response->assertStatus(Response::HTTP_CREATED);

        // Creation invalid typed
        $response = $this->postJson('api/profiles', [
            'firstname' => 55,
            'lastname' => 'Test',
            'image' => 'https://picsum.photos/200/300'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response = $this->postJson('api/profiles', [
            'firstname' => 'Test',
            'lastname' => 55,
            'image' => 'https://picsum.photos/200/300'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Creation missing field
        $response = $this->postJson('api/profiles', [
            'lastname' => 'Test',
            'image' => 'https://picsum.photos/200/300'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response = $this->postJson('api/profiles', [
            'firstname' => 'Test',
            'image' => 'https://picsum.photos/200/300'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_profile_update_public_route() : void
    {
        // unauthenticated user cannot access this route
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put('api/profile/1', [
            'firstname' => 'Test',
            'lastname' => 'Test',
            'image' => 'https://picsum.photos/200/300'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_profile_update_admin_route() : void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ["*"]
        );

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put('api/profile/1', [
            'firstname' => 'Jack',
            'lastname' => 'Green'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'firstname' => 'Jack',
            'lastname' => 'Green'
        ]);

        // Wrongly formated
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put('api/profile/1', [
            'firstname' => 555,
            'lastname' => 'Green'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_profile_destroy_public_route() : void
    {
        // unauthenticated user cannot access this route
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete('api/profile/1');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_profile_destroy_admin_route() : void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ["*"]
        );

        // test can be launch only once (or need reset testing database)
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete('api/profile/4');
        $response->assertStatus(Response::HTTP_OK);

        // not existing users
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete('api/profile/9999');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
