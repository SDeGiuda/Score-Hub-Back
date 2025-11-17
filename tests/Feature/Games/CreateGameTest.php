<?php

declare(strict_types=1);

namespace Tests\Feature\Games;

use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Tests\RequestFactories\CreateGameRequestFactory;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

describe('games', function (): void {
    it('can create a game successfully', function (): void {
        $user = UserFactory::new()->createOne();
        $data = CreateGameRequestFactory::new()->create();

        $response = actingAs($user, 'api')
            ->postJson('/api/games', $data);

        $response->assertStatus(JsonResponse::HTTP_CREATED)
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
                ],
            ]);

        assertDatabaseHas('games', [
            'name' => $data['name'],
            'number_of_players' => $data['number_of_players'],
            'user_id' => $user->id,
        ]);
    });

    it('cannot create a game without authentication', function (): void {
        $data = CreateGameRequestFactory::new()->create();

        $response = postJson('/api/games', $data);

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });

    it('cannot create a game with invalid data - name is required', function (): void {
        $user = UserFactory::new()->createOne();
        $data = CreateGameRequestFactory::new()->create(['name' => '']);

        $response = actingAs($user, 'api')
            ->postJson('/api/games', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name'], 'error.fields');
    });

    it('cannot create a game with invalid data - number_of_players must be at least 1', function (): void {
        $user = UserFactory::new()->createOne();
        $data = CreateGameRequestFactory::new()->create(['number_of_players' => 0]);

        $response = actingAs($user, 'api')
            ->postJson('/api/games', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['number_of_players'], 'error.fields');
    });

    it('cannot create a game with invalid data - has_turns must be boolean', function (): void {
        $user = UserFactory::new()->createOne();
        $data = CreateGameRequestFactory::new()->create(['has_turns' => 'not-a-boolean']);

        $response = actingAs($user, 'api')
            ->postJson('/api/games', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['has_turns'], 'error.fields');
    });

    it('cannot create a game with invalid data - has_teams must be boolean', function (): void {
        $user = UserFactory::new()->createOne();
        $data = CreateGameRequestFactory::new()->create(['has_teams' => 'not-a-boolean']);

        $response = actingAs($user, 'api')
            ->postJson('/api/games', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['has_teams'], 'error.fields');
    });

    it('cannot create a game with invalid data - is_winning must be boolean', function (): void {
        $user = UserFactory::new()->createOne();
        $data = CreateGameRequestFactory::new()->create(['is_winning' => 'not-a-boolean']);

        $response = actingAs($user, 'api')
            ->postJson('/api/games', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['is_winning'], 'error.fields');
    });
});
