<?php

declare(strict_types=1);

namespace Tests\Feature\Games;

use Database\Factories\GameFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Src\Games\App\Controllers\GetGameController;
use Src\Games\App\Resources\GameResource;
use function Pest\Laravel\getJson;

describe('games', function (): void {
    /** @see GetGameController */
    it('retrieves a game and returns a successful response', function (): void {
        $user = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $user->id]);

        getJson("/api/games/{$game->id}")
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson(
                fn (AssertableJson $json): AssertableJson =>
                $json->has(
                    'data',
                    fn (AssertableJson $json): AssertableJson => $json->whereAll(
                        GameResource::make($game)->resolve()
                    )
                )
            );
    });

    it('returns a 404 response when game is not found', function (): void {
        $nonExistentGameId = 99999;

        getJson("/api/games/{$nonExistentGameId}")
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    });
});
