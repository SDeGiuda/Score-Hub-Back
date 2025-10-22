<?php

declare(strict_types=1);

namespace Tests\Feature\Games;

use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Src\Games\App\Controllers\CreateGameControlller;
use Src\Games\App\Resources\GameResource;
use Src\Games\Domain\EndingEnum;
use Src\Games\Domain\Models\Game;
use Tests\RequestFactories\UpsertGameRequestFactory;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

dataset('game-validation-rules', [
    'name is required' => ['name', ''],
    'name must be a string' => ['name', ['array']],
    'number_of_players is required' => ['number_of_players', ''],
    'number_of_players must be numeric' => ['number_of_players', 'abc'],
    'number_of_players must be at least 1' => ['number_of_players', 0],
    'turn_duration must be numeric' => ['turn_duration', 'abc'],
    'round_duration is required' => ['round_duration', ''],
    'round_duration must be numeric' => ['round_duration', 'abc'],
    'rounds is required' => ['rounds', ''],
    'rounds must be numeric' => ['rounds', 'abc'],
    'ending is required' => ['ending', ''],
    'ending must be a valid enum' => ['ending', 'invalid_ending'],
    'has_turns is required' => ['has_turns', ''],
    'has_turns must be boolean' => ['has_turns', 'not_boolean'],
    'has_teams is required' => ['has_teams', ''],
    'has_teams must be boolean' => ['has_teams', 'not_boolean'],
    'min_team_length is required' => ['min_team_length', ''],
    'min_team_length must be numeric' => ['min_team_length', 'abc'],
    'min_team_length must be at least 0' => ['min_team_length', -1],
    'max_team_length is required' => ['max_team_length', ''],
    'max_team_length must be numeric' => ['max_team_length', 'abc'],
    'max_team_length must be max 20' => ['max_team_length', 21],
    'min_points is required' => ['min_points', ''],
    'min_points must be numeric' => ['min_points', 'abc'],
    'max_points is required' => ['max_points', ''],
    'max_points must be numeric' => ['max_points', 'abc'],
    'icon is required' => ['icon', ''],
    'icon must be a string' => ['icon', ['array']],
    'color is required' => ['color', ''],
    'color must be a string' => ['color', ['array']],
    'bg_color is required' => ['bg_color', ''],
    'bg_color must be a string' => ['bg_color', ['array']],
]);

describe('games', function (): void {
    /** @see CreateGameControlller */
    it('can create a game successfully', function (): void {
        $user = UserFactory::new()->createOne();
        $data = UpsertGameRequestFactory::new()->create([
            'ending' => EndingEnum::EndRounds->value,
        ]);

        $response = postJson('/api/games', $data, [
            'Authorization' => 'Bearer ' . auth()->login($user),
        ]);

        $game = Game::query()
            ->where('name', $data['name'])
            ->firstOrFail();

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
            'name' => $data['name'],
            'number_of_players' => $data['number_of_players'],
            'user_id' => $user->id,
        ]);
    });

    it('cannot create a game with invalid data', function (string $field, string|array|int $value): void {
        $user = UserFactory::new()->createOne();
        $data = UpsertGameRequestFactory::new()->create();

        $response = postJson('/api/games', [...$data, $field => $value], [
            'Authorization' => 'Bearer ' . auth()->login($user),
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([$field], 'error.fields');
    })->with('game-validation-rules');

    it('requires authentication to create a game', function (): void {
        $data = UpsertGameRequestFactory::new()->create();

        postJson('/api/games', $data)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });
});
