<?php

declare(strict_types=1);

namespace Src\MatchResults\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Src\MatchResults\App\Requests\StoreResultsRequest;
use Src\MatchResults\Domain\Models\MatchResult;

final readonly class StoreResultsController
{
    public function __invoke(StoreResultsRequest $request): JsonResponse
    {
        $dtos = $request->toDtos();

        try {
            $results = DB::transaction(function () use ($dtos) {
                $savedResults = [];

                foreach ($dtos as $dto) {
                    $savedResults[] = MatchResult::create([
                        'user_id' => $dto->userId,
                        'match_id' => $dto->matchId,
                        'position' => $dto->position,
                        'status' => $dto->status,
                    ]);
                }

                return $savedResults;
            });

            return response()->json([
                'data' => $results,
                'message' => 'Results stored successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to store results',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
