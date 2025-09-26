<?php

declare(strict_types=1);

namespace Tests\Feature\Users;

use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\postJson;

describe('users', function (): void {
    it('retrieves a user and returns a successful response', function (): void {
        $existingUser = UserFactory::new()->createOne(['password' => 'password']);

        $response = postJson('api/users/login', ['email'=>$existingUser->email, 'password'=>'password'])
            ->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJson(
            fn (AssertableJson $json): \Illuminate\Testing\Fluent\AssertableJson =>
            $json->has(
                'data',
                fn (AssertableJson $json): \Illuminate\Testing\Fluent\AssertableJson =>
                $json->hasAll([
                    'access_token',
                    'token_type',
                    'expires_in',
                ])->etc()
            )
        );
    });

    it('returns a 404 response when user is not found', function (): void {
        $nonExistentUserEmail = 'non-existent-email@email.com';

        $response = postJson('api/users/login', ['email'=>$nonExistentUserEmail, 'password'=>'password'])
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    });
    it('returns a 401 response when password is incorrect', function (): void {
        $existingUser = UserFactory::new()->createOne(['password' => 'password']);

        $response = postJson('api/users/login', ['email'=>$existingUser->email, 'password'=>'wrong_password'])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });
});
