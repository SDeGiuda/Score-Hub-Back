<?php

declare(strict_types=1);

namespace Tests\Feature\Matches;

use Database\Factories\GameMatchFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;

describe('matches', function (): void {
    it('can delete a match', function (): void {
        $user = UserFactory::new()->createOne();
        $match = GameMatchFactory::new()->createOne(['creator_id' => $user->id]);

        $response = actingAs($user, 'api')
            ->deleteJson("/api/game-match/{$match->id}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'message' => 'Match deleted successfully',
            ]);

        assertDatabaseMissing('matches', [
            'id' => $match->id,
        ]);
    });

    it('cannot delete a match without authentication', function (): void {
        $match = GameMatchFactory::new()->createOne();

        $response = deleteJson("/api/game-match/{$match->id}");

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });

    it('returns 404 when deleting non-existent match', function (): void {
        $user = UserFactory::new()->createOne();

        $response = actingAs($user, 'api')
            ->deleteJson('/api/game-match/99999');

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    });
});
