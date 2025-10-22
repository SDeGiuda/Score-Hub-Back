<?php

declare(strict_types=1);

namespace Tests\Feature\Users;

use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Src\Users\App\Controllers\LogoutController;
use function Pest\Laravel\postJson;

describe('users', function (): void {
    /** @see LogoutController */
    it('can logout an authenticated user successfully', function (): void {
        $user = UserFactory::new()->createOne();
        $token = auth()->login($user);

        postJson('/api/logout', [], [
            'Authorization' => "Bearer {$token}",
        ])
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    });

    it('returns unauthorized when user is not authenticated', function (): void {
        postJson('/api/logout')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });

    it('returns unauthorized when token is invalid', function (): void {
        postJson('/api/logout', [], [
            'Authorization' => 'Bearer invalid_token',
        ])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });
});
