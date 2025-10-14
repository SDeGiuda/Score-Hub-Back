<?php

declare(strict_types=1);

namespace Src\Games\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Games\Domain\Actions\DeleteGameAction;
use Src\Games\Domain\Models\Game;

class DeleteGameController
{
    public function __invoke(Game $game, DeleteGameAction $deleteGameAction): JsonResponse
    {
        $deleteGameAction->execute($game);

        return response()->json([
            'message' => 'Game deleted successfully',
        ], 200);
    }
}
