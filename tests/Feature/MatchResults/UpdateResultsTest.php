<?php

declare(strict_types=1);

namespace Tests\Feature\MatchResults;

use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Tests\RequestFactories\UpdateResultsRequestFactory;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\patchJson;

describe('match results', function (): void {
    it('can update results successfully', function (): void {
        $user = UserFactory::new()->createOne();
        $data = UpdateResultsRequestFactory::new()->create();

        $response = actingAs($user, 'api')
            ->patchJson('/api/results', $data);

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
            ]);

        // Verify database was updated
        /** @var array<int, array{user_id: int, position: int, points: int}> $results */
        $results = $data['results'];
        foreach ($results as $result) {
            assertDatabaseHas('match_results', [
                'user_id' => $result['user_id'],
                'position' => $result['position'],
                'points' => $result['points'],
                'status' => 'active',
            ]);
        }
    });

    it('cannot update results without authentication', function (): void {
        $data = UpdateResultsRequestFactory::new()->create();

        $response = patchJson('/api/results', $data);

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    });

    it('cannot update results with invalid data - results is required', function (): void {
        $user = UserFactory::new()->createOne();
        $data = UpdateResultsRequestFactory::new()->create();
        unset($data['results']);

        $response = actingAs($user, 'api')
            ->patchJson('/api/results', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['results'], 'error.fields');
    });

    it('cannot update results with invalid data - match_id is required', function (): void {
        $user = UserFactory::new()->createOne();
        $data = UpdateResultsRequestFactory::new()->create();
        unset($data['match_id']);

        $response = actingAs($user, 'api')
            ->patchJson('/api/results', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['match_id'], 'error.fields');
    });

    it('cannot update results with invalid data - match_id must exist', function (): void {
        $user = UserFactory::new()->createOne();
        $data = UpdateResultsRequestFactory::new()->create(['match_id' => 99999]);

        $response = actingAs($user, 'api')
            ->patchJson('/api/results', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['match_id'], 'error.fields');
    });

    it('cannot update results with invalid data - user_id must exist', function (): void {
        $user = UserFactory::new()->createOne();
        $data = UpdateResultsRequestFactory::new()->create();
        /** @var array<int, array{user_id: int, position: int, points: int}> $results */
        $results = $data['results'];
        $results[0]['user_id'] = 99999;
        $data['results'] = $results;

        $response = actingAs($user, 'api')
            ->patchJson('/api/results', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['results.0.user_id'], 'error.fields');
    });

    it('cannot update results with invalid data - position must be at least 1', function (): void {
        $user = UserFactory::new()->createOne();
        $data = UpdateResultsRequestFactory::new()->create();
        /** @var array<int, array{user_id: int, position: int, points: int}> $results */
        $results = $data['results'];
        $results[0]['position'] = 0;
        $data['results'] = $results;

        $response = actingAs($user, 'api')
            ->patchJson('/api/results', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['results.0.position'], 'error.fields');
    });

    it('can update multiple results in a single request', function (): void {
        $user = UserFactory::new()->createOne();
        $data = UpdateResultsRequestFactory::new()->create();

        // Ensure we have at least 2 results
        expect($data['results'])->toHaveCount(2);

        $response = actingAs($user, 'api')
            ->patchJson('/api/results', $data);

        $response->assertStatus(JsonResponse::HTTP_OK);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(2);

        // Verify all results were updated
        /** @var array<int, array{user_id: int, position: int, points: int}> $results */
        $results = $data['results'];
        foreach ($results as $result) {
            assertDatabaseHas('match_results', [
                'user_id' => $result['user_id'],
                'position' => $result['position'],
                'points' => $result['points'],
            ]);
        }
    });
});
