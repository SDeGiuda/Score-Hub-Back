<?php

declare(strict_types=1);

namespace Src\Matches\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Src\Matches\App\Requests\CreateMatchRequest;
use Src\Matches\Domain\Models\GameMatch;
use Src\MatchResults\Domain\Enums\ResultStatusEnum;
use Src\MatchResults\Domain\Models\MatchResult;
use Src\Users\Domain\Models\User;

class CreateMatchController
{
    public function __invoke(CreateMatchRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        $match = GameMatch::create([
            'name' => $dto->name,
            'game_id' => $dto->gameId,
            'creator_id' => $dto->creatorId,
        ]);

        foreach ($dto->players as $player) {
            $user = User::whereUsername($player)->first();
            if (! $user) {
                Log::error('User: ' . $player . ' not found');

                continue;
            }
            MatchResult::create([
                'match_id' => $match->id,
                'user_id'=> $user->id,
                'position' => 0,
                'status' => ResultStatusEnum::ACTIVE,
            ]);
        }


        return response()->json($match);
    }
}
