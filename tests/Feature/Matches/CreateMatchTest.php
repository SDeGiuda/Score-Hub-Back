<?php

declare(strict_types=1);

namespace Tests\Feature\Matches;

use Database\Factories\GameFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Src\Matches\App\Controllers\CreateMatchController;
use Src\Matches\Domain\Models\GameMatch;
use Src\MatchResults\Domain\Enums\ResultStatusEnum;
use Tests\RequestFactories\CreateMatchRequestFactory;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

dataset('match-validation-rules', [
    'creator_id is required' => ['creator_id', ''],
    'creator_id must exist in users table' => ['creator_id', 99999],
    'game_id is required' => ['game_id', ''],
    'game_id must exist in games table' => ['game_id', 99999],
    'players is required' => ['players', ''],
    'players must be an array' => ['players', 'not_an_array'],
]);

describe('matches', function (): void {
    /** @see CreateMatchController */
    it('can create a match successfully', function (): void {
        $creator = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $creator->id]);
        $player1 = UserFactory::new()->createOne(['name' => 'player1']);
        $player2 = UserFactory::new()->createOne(['name' => 'player2']);

        $data = CreateMatchRequestFactory::new()->create([
            'creator_id' => $creator->id,
            'game_id' => $game->id,
            'players' => ['player1', 'player2'],
        ]);

        $response = postJson('/api/matches', $data);

        $match = GameMatch::query()
            ->where('creator_id', $creator->id)
            ->where('game_id', $game->id)
            ->firstOrFail();

        $response
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'id' => $match->id,
                'creator_id' => $creator->id,
                'game_id' => $game->id,
            ]);

        assertDatabaseHas('matches', [
            'id' => $match->id,
            'creator_id' => $creator->id,
            'game_id' => $game->id,
        ]);

        // Verify match results were created for players
        assertDatabaseHas('match_results', [
            'match_id' => $match->id,
            'user_id' => $player1->id,
            'position' => 0,
            'status' => ResultStatusEnum::ACTIVE->value,
        ]);

        assertDatabaseHas('match_results', [
            'match_id' => $match->id,
            'user_id' => $player2->id,
            'position' => 0,
            'status' => ResultStatusEnum::ACTIVE->value,
        ]);
    });

    it('can create a match with guest players', function (): void {
        $creator = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $creator->id]);

        $data = CreateMatchRequestFactory::new()->create([
            'creator_id' => $creator->id,
            'game_id' => $game->id,
            'players' => ['guest', 'guest'],
        ]);

        $response = postJson('/api/matches', $data);

        $response->assertStatus(JsonResponse::HTTP_OK);

        assertDatabaseHas('matches', [
            'creator_id' => $creator->id,
            'game_id' => $game->id,
        ]);
    });

    it('skips creating match results for non-existent players', function (): void {
        $creator = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $creator->id]);

        $data = CreateMatchRequestFactory::new()->create([
            'creator_id' => $creator->id,
            'game_id' => $game->id,
            'players' => ['nonexistent_player'],
        ]);

        $response = postJson('/api/matches', $data);

        $match = GameMatch::query()
            ->where('creator_id', $creator->id)
            ->where('game_id', $game->id)
            ->firstOrFail();

        $response->assertStatus(JsonResponse::HTTP_OK);

        // Match should be created but no results for non-existent player
        expect($match->results)->toHaveCount(0);
    });

    it('cannot create a match with invalid data', function (string $field, string|array|int $value): void {
        $data = CreateMatchRequestFactory::new()->create();

        $response = postJson('/api/matches', [...$data, $field => $value]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([$field], 'error.fields');
    })->with('match-validation-rules');
});
