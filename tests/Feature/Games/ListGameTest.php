<?php

declare(strict_types=1);

namespace Tests\Feature\Games;

use Database\Factories\GameFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Src\Games\App\Controllers\ListGameController;
use Src\Games\App\Resources\GameResource;
use Src\Games\Domain\Models\Game;
use function Pest\Laravel\getJson;

describe('games', function (): void {
    /** @see ListGameController */
    it('can list all games successfully', function (): void {
        $user = UserFactory::new()->createOne();
        $games = GameFactory::new()->count(3)->create(['user_id' => $user->id]);

        getJson('/api/games')
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson(
                fn (AssertableJson $json): AssertableJson =>
                $json->has('data', 3)
                    ->has(
                        'data.0',
                        fn (AssertableJson $json): AssertableJson => $json->whereAll(
                            GameResource::make($games->first())->resolve()
                        )
                    )
            );
    });

    it('returns empty array when no games exist', function (): void {
        getJson('/api/games')
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson(['data' => []]);
    });

    it('handles errors gracefully', function (): void {
        // Force an error by mocking the Game model to throw an exception
        $this->mock(Game::class, function ($mock): void {
            $mock->shouldReceive('all')
                ->andThrow(new \Exception('Database error'));
        });

        getJson('/api/games')
            ->assertStatus(JsonResponse::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson(['message' => 'No se pudieron obtener los juegos.']);
    });
});
