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
        Log::info('Creating a new match', [
            'request_data' => $request->all(),
        ]);
        $dto = $request->toDto();

        try {
            $match = DB::transaction(function () use ($dto): array {
                // Create the match
                $match = GameMatch::create([
                    'name' => $dto->name,
                    'game_id' => $dto->gameId,
                    'creator_id' => $dto->creatorId,
                ]);

                $playersAdded = 0;
                $playersNotFound = [];

                // Create match results for each player
                foreach ($dto->players as $player) {
                    $user = User::whereUsername($player)->first();

                    if (! $user) {
                        Log::warning('User not found during match creation', [
                            'username' => $player,
                            'match_id' => $match->id,
                        ]);
                        $playersNotFound[] = $player;

                        continue;
                    }

                    MatchResult::create([
                        'match_id' => $match->id,
                        'user_id' => $user->id,
                        'position' => 0,
                        'status' => ResultStatusEnum::ACTIVE,
                    ]);

                    $playersAdded++;
                }

                // Ensure at least one player was added
                if ($playersAdded === 0) {
                    throw new \Exception('No valid players were added to the match');
                }

                // Load relationships for response
                $match->load('game', 'creator', 'results.player');

                return [
                    'match' => $match,
                    'players_added' => $playersAdded,
                    'players_not_found' => $playersNotFound,
                ];
            });

            $matchResource = GameMatchResource::make($match['match'])
                ->additional([
                    'meta' => [
                        'players_added' => $match['players_added'],
                        'players_not_found' => $match['players_not_found'],
                    ],
                    'message' => 'Match created successfully',
                ]);

            return $matchResource->response()->setStatusCode(201);
        } catch (\Exception $e) {
            Log::error('Failed to create match', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Failed to create match',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
