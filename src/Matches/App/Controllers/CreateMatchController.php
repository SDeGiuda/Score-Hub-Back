<?php

declare(strict_types=1);

namespace Src\Matches\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Matches\App\Requests\CreateMatchRequest;
use Src\Matches\Domain\Models\GameMatch;

class CreateMatchController
{
    public function __invoke(CreateMatchRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        $match = GameMatch::create([
            "game_id" => $dto->gameId,
            "creator_id" => $dto->creatorId,
        ]);
        return response()->json($match);
    }
}
