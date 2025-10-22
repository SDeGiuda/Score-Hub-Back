<?php

declare(strict_types=1);

namespace Tests\Feature\Matches;

use Database\Factories\GameFactory;
use Database\Factories\GameMatchFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Src\Matches\App\Controllers\GetMatchController;
use function Pest\Laravel\getJson;

describe('matches', function (): void {
    /** @see GetMatchController */
    it('retrieves a match and returns a successful response', function (): void {
        $user = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne(['user_id' => $user->id]);
        $match = GameMatchFactory::new()->createOne([
            'creator_id' => $user->id,
            'game_id' => $game->id,
        ]);

        getJson("/api/matches/{$match->id}")
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'id' => $match->id,
                'creator_id' => $user->id,
                'game_id' => $game->id,
            ]);
    });

    it('returns a 404 response when match is not found', function (): void {
        $nonExistentMatchId = 99999;

        getJson("/api/matches/{$nonExistentMatchId}")
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    });
});
