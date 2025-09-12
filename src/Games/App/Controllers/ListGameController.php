<?php

declare(strict_types=1);

namespace Src\Games\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Games\App\Resources\GameResource;
use Src\Games\Domain\Models\Game;

class ListGameController
{
    public function __invoke(): JsonResponse
    {
        return GameResource::collection(Game::all())->response();
    }

}
