<?php

declare(strict_types=1);

namespace Src\Games\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Games\App\Requests\UpsertGameRequest;
use Src\Games\App\Resources\GameResource;
use Src\Games\Domain\Actions\UpdateGameAction;
use Src\Games\Domain\Models\Game;

class UpdateGameController
{
    public function __invoke(UpsertGameRequest $request, UpdateGameAction $action,Game $game): JsonResponse
    {
        $updatedGame = $action->execute($game,$request->toDto());
        return GameResource::make($updatedGame)->response();
    }
}
