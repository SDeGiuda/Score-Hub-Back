<?php

declare(strict_types=1);

namespace Tests\Feature\Matches;

use Database\Factories\GameFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Tests\RequestFactories\CreateMatchRequestFactory;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

describe('matches', function (): void {
    it('can create a match successfully', function (): void {
        $user = UserFactory::new()->createOne();
        $data = CreateMatchRequestFactory::new()->create();

        $response = actingAs($user, 'api')
            ->postJson('/api/game-match', $data);

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'game',
                    'creator',
                    'results',
                ],
                'meta' => [
                    'players_added',
                    'players_not_found',
                ],
            ])
            ->assertJson([
                'message' => 'Match created successfully',
            ]);

        assertDatabaseHas('matches', [
            'name' => $data['name'],
            'game_id' => $data['game_id'],
            'creator_id' => $data['creator_id'],
        ]);

        // Verify match results were created for each player
        /** @var array<int, string> $players */
        $players = $data['players'];
        foreach ($players as $playerUsername) {
            $player = \Src\Users\Domain\Models\User::whereUsername($playerUsername)->first();
            if ($player === null) {
                continue;
            }
            assertDatabaseHas('match_results', [
                'user_id' => $player->id,
                'status' => 'active',
            ]);
        }
    });

    it('cannot create a match without authentication', function (): void {
        $data = CreateMatchRequestFactory::new()->create();

        $response = postJson('/api/game-match', $data);

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });

    it('cannot create a match with invalid data - name is required', function (): void {
        $user = UserFactory::new()->createOne();
        $data = CreateMatchRequestFactory::new()->create(['name' => '']);

        $response = actingAs($user, 'api')
            ->postJson('/api/game-match', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name'], 'error.fields');
    });

    it('cannot create a match with invalid data - creator_id must exist', function (): void {
        $user = UserFactory::new()->createOne();
        $data = CreateMatchRequestFactory::new()->create(['creator_id' => 99999]);

        $response = actingAs($user, 'api')
            ->postJson('/api/game-match', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['creator_id'], 'error.fields');
    });

    it('cannot create a match with invalid data - game_id must exist', function (): void {
        $user = UserFactory::new()->createOne();
        $data = CreateMatchRequestFactory::new()->create(['game_id' => 99999]);

        $response = actingAs($user, 'api')
            ->postJson('/api/game-match', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['game_id'], 'error.fields');
    });

    it('cannot create a match with invalid data - players must be an array', function (): void {
        $user = UserFactory::new()->createOne();
        $data = CreateMatchRequestFactory::new()->create(['players' => 'not-an-array']);

        $response = actingAs($user, 'api')
            ->postJson('/api/game-match', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['players'], 'error.fields');
    });

    // do later: Fix guest player validation in CreateMatchRequest
    // it('can create a match with guest player', function (): void {
    //     $user = UserFactory::new()->createOne();
    //     $game = GameFactory::new()->createOne();
    //     $player = UserFactory::new()->createOne();

    //     $data = [
    //         'name' => 'Test Match',
    //         'creator_id' => $user->id,
    //         'game_id' => $game->id,
    //         'players' => [$player->username, 'guest'],
    //     ];

    //     $response = actingAs($user, 'api')
    //         ->postJson('/api/game-match', $data);

    //     $response->assertStatus(JsonResponse::HTTP_CREATED);

    //     assertDatabaseHas('matches', [
    //         'name' => 'Test Match',
    //     ]);
    // });
});
