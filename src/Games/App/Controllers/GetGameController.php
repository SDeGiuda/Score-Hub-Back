<?php

declare(strict_types=1);

namespace Src\Games\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Games\App\Resources\GameResource;
use Src\Games\Domain\Models\Game;

class GetGameController
{
    public function __invoke(Game $game): JsonResponse
    {
        return GameResource::make($game)->response();
    }
}
