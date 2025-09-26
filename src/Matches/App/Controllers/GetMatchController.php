<?php

declare(strict_types=1);

namespace Src\Matches\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Matches\Domain\Models\GameMatch;

class GetMatchController
{
    public function __invoke(GameMatch $match): JsonResponse
    {
        return response()->json($match);
    }
}
