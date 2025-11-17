<?php

declare(strict_types=1);

namespace Tests\Feature\MatchResults;

use Database\Factories\GameMatchFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Src\MatchResults\Domain\Enums\ResultStatusEnum;
use Src\MatchResults\Domain\Models\MatchResult;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

describe('match results', function (): void {
    it('can list active results for authenticated user', function (): void {
        $user = UserFactory::new()->createOne();
        $match1 = GameMatchFactory::new()->createOne();
        $match2 = GameMatchFactory::new()->createOne();

        // Create active results for the user in different matches
        MatchResult::create([
            'match_id' => $match1->id,
            'user_id' => $user->id,
            'position' => 1,
            'points' => 50,
            'status' => ResultStatusEnum::ACTIVE,
        ]);

        MatchResult::create([
            'match_id' => $match2->id,
            'user_id' => $user->id,
            'position' => 2,
            'points' => 30,
            'status' => ResultStatusEnum::ACTIVE,
        ]);

        $response = actingAs($user, 'api')
            ->getJson('/api/results');

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'match_id',
                        'position',
                        'created_at',
                    ],
                ],
                'links',
                'meta',
            ]);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(2);
    });

    it('only lists active results, not pending ones', function (): void {
        $user = UserFactory::new()->createOne();
        $match1 = GameMatchFactory::new()->createOne();
        $match2 = GameMatchFactory::new()->createOne();

        // Create one active and one pending result
        MatchResult::create([
            'match_id' => $match1->id,
            'user_id' => $user->id,
            'position' => 1,
            'points' => 50,
            'status' => ResultStatusEnum::ACTIVE,
        ]);

        MatchResult::create([
            'match_id' => $match2->id,
            'user_id' => $user->id,
            'position' => 1,
            'points' => 100,
            'status' => ResultStatusEnum::PENDING,
        ]);

        $response = actingAs($user, 'api')
            ->getJson('/api/results');

        $response->assertStatus(JsonResponse::HTTP_OK);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(1);
    });

    it('cannot list results without authentication', function (): void {
        $response = getJson('/api/results');

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });

    it('returns empty list when user has no active results', function (): void {
        $user = UserFactory::new()->createOne();

        $response = actingAs($user, 'api')
            ->getJson('/api/results');

        $response->assertStatus(JsonResponse::HTTP_OK);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(0);
    });

    it('paginates results', function (): void {
        $user = UserFactory::new()->createOne();
        $matches = GameMatchFactory::new()->createMany(20);

        // Create 20 active results
        foreach ($matches as $match) {
            MatchResult::create([
                'match_id' => $match->id,
                'user_id' => $user->id,
                'position' => 1,
                'points' => 50,
                'status' => ResultStatusEnum::ACTIVE,
            ]);
        }

        $response = actingAs($user, 'api')
            ->getJson('/api/results');

        $response->assertStatus(JsonResponse::HTTP_OK);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(15); // Default pagination is 15
        expect($response->json('meta.total'))->toBe(20);
    });
});
