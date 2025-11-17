<?php

declare(strict_types=1);

namespace Tests\Feature\Games;

use Database\Factories\GameFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Tests\RequestFactories\CreateGameRequestFactory;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

describe('games', function (): void {
    it('can update a game', function (): void {
        $user = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $user->id]);
        $data = CreateGameRequestFactory::new()->create([
            'name' => 'Updated Game Name',
        ]);

        $response = actingAs($user, 'api')
            ->putJson("/api/games/{$game->id}", $data);

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $game->id,
                    'name' => 'Updated Game Name',
                ],
            ]);

        assertDatabaseHas('games', [
            'id' => $game->id,
            'name' => 'Updated Game Name',
        ]);
    });

    it('cannot update a game without authentication', function (): void {
        $game = GameFactory::new()->createOne();
        $data = CreateGameRequestFactory::new()->create();

        $response = putJson("/api/games/{$game->id}", $data);

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });

    it('cannot update a game with invalid data', function (): void {
        $user = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $user->id]);
        $data = CreateGameRequestFactory::new()->create(['name' => '']);

        $response = actingAs($user, 'api')
            ->putJson("/api/games/{$game->id}", $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name'], 'error.fields');
    });

    it('returns 404 when updating non-existent game', function (): void {
        $user = UserFactory::new()->createOne();
        $data = CreateGameRequestFactory::new()->create();

        $response = actingAs($user, 'api')
            ->putJson('/api/games/99999', $data);

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    });
});
