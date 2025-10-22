<?php

declare(strict_types=1);

namespace Tests\Feature\MatchResults;

use Database\Factories\GameFactory;
use Database\Factories\GameMatchFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Src\MatchResults\App\Controllers\StoreResultsController;
use Src\MatchResults\Domain\Enums\ResultStatusEnum;
use Tests\RequestFactories\StoreResultsRequestFactory;
use function Pest\Laravel\postJson;

dataset('result-validation-rules', [
    'results is required' => ['results', ''],
    'results must be an array' => ['results', 'not_an_array'],
    'match_id is required' => ['match_id', ''],
    'match_id must be an integer' => ['match_id', 'not_integer'],
]);

describe('match results', function (): void {
    /** @see StoreResultsController */
    it('can store match results successfully', function (): void {
        $user1 = UserFactory::new()->createOne();
        $user2 = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $user1->id]);
        $match = GameMatchFactory::new()->createOne([
            'creator_id' => $user1->id,
            'game_id' => $game->id,
        ]);

        $data = StoreResultsRequestFactory::new()->create([
            'match_id' => $match->id,
            'results' => [
                [
                    'user_id' => $user1->id,
                    'position' => 1,
                    'status' => ResultStatusEnum::ACTIVE->value,
                ],
                [
                    'user_id' => $user2->id,
                    'position' => 2,
                    'status' => ResultStatusEnum::PENDING->value,
                ],
            ],
        ]);

        $response = postJson('/api/match-results', $data);

        $response->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    });

    it('can store results with single player', function (): void {
        $user = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $user->id]);
        $match = GameMatchFactory::new()->createOne([
            'creator_id' => $user->id,
            'game_id' => $game->id,
        ]);

        $data = StoreResultsRequestFactory::new()->create([
            'match_id' => $match->id,
            'results' => [
                [
                    'user_id' => $user->id,
                    'position' => 1,
                    'status' => ResultStatusEnum::ACTIVE->value,
                ],
            ],
        ]);

        $response = postJson('/api/match-results', $data);

        $response->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    });

    it('cannot store results with invalid data', function (string $field, string|array|int $value): void {
        $data = StoreResultsRequestFactory::new()->create();

        $response = postJson('/api/match-results', [...$data, $field => $value]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([$field], 'error.fields');
    })->with('result-validation-rules');

    it('cannot store results with non-existent match', function (): void {
        $user = UserFactory::new()->createOne();
        $nonExistentMatchId = 99999;

        $data = StoreResultsRequestFactory::new()->create([
            'match_id' => $nonExistentMatchId,
            'results' => [
                [
                    'user_id' => $user->id,
                    'position' => 1,
                    'status' => ResultStatusEnum::ACTIVE->value,
                ],
            ],
        ]);

        $response = postJson('/api/match-results', $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['match_id'], 'error.fields');
    });

    it('cannot store results with non-existent user', function (): void {
        $user = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $user->id]);
        $match = GameMatchFactory::new()->createOne([
            'creator_id' => $user->id,
            'game_id' => $game->id,
        ]);
        $nonExistentUserId = 99999;

        $data = StoreResultsRequestFactory::new()->create([
            'match_id' => $match->id,
            'results' => [
                [
                    'user_id' => $nonExistentUserId,
                    'position' => 1,
                    'status' => ResultStatusEnum::ACTIVE->value,
                ],
            ],
        ]);

        $response = postJson('/api/match-results', $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['results.0.user_id'], 'error.fields');
    });
});
