<?php

declare(strict_types=1);

namespace Tests\Feature\Matches;

use Database\Factories\GameMatchFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

describe('matches', function (): void {
    it('can retrieve a match', function (): void {
        $user = UserFactory::new()->createOne();
        $match = GameMatchFactory::new()->createOne();

        $response = actingAs($user, 'api')
            ->getJson("/api/game-match/{$match->id}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'game',
                    'creator',
                ],
            ])
            ->assertJson([
                'data' => [
                    'id' => $match->id,
                    'name' => $match->name,
                ],
            ]);
    });

    it('cannot retrieve a match without authentication', function (): void {
        $match = GameMatchFactory::new()->createOne();

        $response = getJson("/api/game-match/{$match->id}");

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });

    it('returns 404 when match does not exist', function (): void {
        $user = UserFactory::new()->createOne();

        $response = actingAs($user, 'api')
            ->getJson('/api/game-match/99999');

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    });
});