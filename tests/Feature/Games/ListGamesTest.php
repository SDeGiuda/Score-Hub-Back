<?php

declare(strict_types=1);

namespace Tests\Feature\Games;

use Database\Factories\GameFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

describe('games', function (): void {
    it('can list games', function (): void {
        $user = UserFactory::new()->createOne();
        GameFactory::new()->createMany(5);

        $response = actingAs($user, 'api')
            ->getJson('/api/games');

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'number_of_players',
                        'has_turns',
                        'has_teams',
                    ],
                ],
                'links',
                'meta',
            ]);
    });

    it('cannot list games without authentication', function (): void {
        GameFactory::new()->createMany(5);

        $response = getJson('/api/games');

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });

    it('can filter games by name', function (): void {
        $user = UserFactory::new()->createOne();
        $game1 = GameFactory::new()->createOne(['name' => 'Truco']);
        $game2 = GameFactory::new()->createOne(['name' => 'Poker']);
        $game3 = GameFactory::new()->createOne(['name' => 'Truco Argentino']);

        $response = actingAs($user, 'api')
            ->getJson('/api/games?filter[name]=Truco');

        $response->assertStatus(JsonResponse::HTTP_OK);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(2);
    });

    it('can filter classic games (games with user_id = 0)', function (): void {
        $user = UserFactory::new()->createOne();
        $classicGame = GameFactory::new()->createOne(['user_id' => 0]);
        $userGame = GameFactory::new()->createOne(['user_id' => $user->id]);

        $response = actingAs($user, 'api')
            ->getJson('/api/games?filter[classic]=true');

        $response->assertStatus(JsonResponse::HTTP_OK);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(1);
        expect($responseData[0]['id'])->toBe($classicGame->id);
    });

    it('can filter user\'s own games', function (): void {
        $user = UserFactory::new()->createOne();
        $userGame = GameFactory::new()->createOne(['user_id' => $user->id]);
        $otherUserGame = GameFactory::new()->createOne(['user_id' => UserFactory::new()->createOne()->id]);

        $response = actingAs($user, 'api')
            ->getJson('/api/games?filter[me]=true');

        $response->assertStatus(JsonResponse::HTTP_OK);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(1);
        expect($responseData[0]['id'])->toBe($userGame->id);
    });

    it('can paginate games', function (): void {
        $user = UserFactory::new()->createOne();
        GameFactory::new()->createMany(30);

        $response = actingAs($user, 'api')
            ->getJson('/api/games?per_page=10');

        $response->assertStatus(JsonResponse::HTTP_OK);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(10);
        expect($response->json('meta.total'))->toBeGreaterThanOrEqual(30);
    });
});