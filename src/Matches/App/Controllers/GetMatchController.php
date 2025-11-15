<?php

declare(strict_types=1);

namespace Src\Matches\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Matches\App\Resources\GameMatchResource;
use Src\Matches\Domain\Models\GameMatch;

final readonly class GetMatchController
{
    public function __invoke(GameMatch $gameMatch): JsonResponse
    {
        // Load relationships for complete response
        $gameMatch->load('game', 'creator', 'results.player');

        return GameMatchResource::make($gameMatch)->response();
    }
}
