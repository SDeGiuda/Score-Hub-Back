<?php

declare(strict_types=1);

namespace Tests\Feature\Games;

use Database\Factories\GameFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Src\Games\App\Controllers\UpdateGameController;
use Src\Games\App\Resources\GameResource;
use Src\Games\Domain\EndingEnum;
use Tests\RequestFactories\UpsertGameRequestFactory;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

describe('games', function (): void {
    /** @see UpdateGameController */
    it('can update a game successfully', function (): void {
        $user = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $user->id]);

        $updateData = UpsertGameRequestFactory::new()->create([
            'name' => 'Updated Game Name',
            'ending' => EndingEnum::ReachMaxScore->value,
        ]);

        $response = putJson("/api/games/{$game->id}", $updateData, [
            'Authorization' => 'Bearer ' . auth()->login($user),
        ]);

        $game->refresh();

        $response
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

        assertDatabaseHas('games', [
            'id' => $game->id,
            'name' => 'Updated Game Name',
            'ending' => EndingEnum::ReachMaxScore->value,
        ]);
    });

    it('cannot update a game with invalid data', function (string $field, string|array|int $value): void {
        $user = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $user->id]);
        $data = UpsertGameRequestFactory::new()->create();

        $response = putJson("/api/games/{$game->id}", [...$data, $field => $value], [
            'Authorization' => 'Bearer ' . auth()->login($user),
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([$field], 'error.fields');
    })->with('game-validation-rules');

    it('returns 404 when updating non-existent game', function (): void {
        $user = UserFactory::new()->createOne();
        $nonExistentGameId = 99999;
        $data = UpsertGameRequestFactory::new()->create();

        putJson("/api/games/{$nonExistentGameId}", $data, [
            'Authorization' => 'Bearer ' . auth()->login($user),
        ])
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    });

    it('requires authentication to update a game', function (): void {
        $user = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $user->id]);
        $data = UpsertGameRequestFactory::new()->create();

        putJson("/api/games/{$game->id}", $data)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });
});
