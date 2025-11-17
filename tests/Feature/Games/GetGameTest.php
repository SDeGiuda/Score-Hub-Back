<?php

declare(strict_types=1);

namespace Tests\Feature\Games;

use Database\Factories\GameFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

describe('games', function (): void {
    it('can retrieve a game', function (): void {
        $user = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne();

        $response = actingAs($user, 'api')
            ->getJson("/api/games/{$game->id}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'number_of_players',
                    'has_turns',
                    'has_teams',
                    'icon',
                    'color',
                    'bg_color',
                    'starting_points',
                    'finishing_points',
                    'is_winning',
                ],
            ])
            ->assertJson([
                'data' => [
                    'id' => $game->id,
                    'name' => $game->name,
                ],
            ]);
    });

    it('cannot retrieve a game without authentication', function (): void {
        $game = GameFactory::new()->createOne();

        $response = getJson("/api/games/{$game->id}");

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });

    it('returns 404 when game does not exist', function (): void {
        $user = UserFactory::new()->createOne();

        $response = actingAs($user, 'api')
            ->getJson('/api/games/99999');

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    });
});
