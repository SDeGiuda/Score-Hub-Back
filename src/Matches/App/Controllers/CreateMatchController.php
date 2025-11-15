<?php

declare(strict_types=1);

namespace Src\Matches\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Matches\App\Requests\CreateMatchRequest;
use Src\Matches\App\Resources\GameMatchResource;
use Src\Matches\Domain\Models\GameMatch;
use Src\MatchResults\Domain\Enums\ResultStatusEnum;
use Src\MatchResults\Domain\Models\MatchResult;
use Src\Users\Domain\Models\User;

final readonly class CreateMatchController
{
    public function __invoke(CreateMatchRequest $request): JsonResponse
    {
        $dto = $request->toDto();

        try {
            $match = DB::transaction(function () use ($dto): array {

                $match = GameMatch::create([
                    'name' => $dto->name,
                    'game_id' => $dto->gameId,
                    'creator_id' => $dto->creatorId,
                ]);

                foreach ($dto->players as $player) {
                    $user = User::whereUsername($player)->first();

                    MatchResult::create([
                        'match_id' => $match->id,
                        'user_id' => $user->id,
                        'position' => 0,
                        'status' => ResultStatusEnum::ACTIVE,
                    ]);

                }

                $match->load('game', 'creator', 'results.player');

                return [
                    'match' => $match,
                ];
            });

            $matchResource = GameMatchResource::make($match['match'])
                ->additional([
                    'meta' => [
                        'players_added' => collect($dto->players)->count(),
                        'players_not_found' => 0,
                    ],
                    'message' => 'Match created successfully',
                ]);

            return $matchResource->response()->setStatusCode(201);
        } catch (\Throwable $e) {
            Log::error('Failed to create match', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Failed to create match',
                'message' => "unknown error",
            ], 500);
        }
    }
}
