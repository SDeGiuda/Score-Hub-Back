<?php

declare(strict_types=1);

namespace Src\Games\App\Controllers;

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Src\Games\App\Requests\UpsertGameRequest;
use Src\Games\App\Resources\GameResource;
use Src\Games\Domain\Actions\UpdateGameAction;
use Src\Games\Domain\Models\Game;
use Src\Users\Domain\Models\User;

final readonly class UpdateGameController
{
    public function __invoke(
        UpsertGameRequest $request,
        UpdateGameAction $action,
        Game $game,
        #[CurrentUser] User $authenticatedUser
    ): JsonResponse {
        // Authorization: Only the game creator can update it
        if ($game->user_id !== $authenticatedUser->id) {
            return response()->json([
                'error' => 'forbidden',
                'message' => 'You are not authorized to update this game.',
            ], 403);
        }

        $updatedGame = $action->execute($game, $request->toDto());

        return GameResource::make($updatedGame)->response();
    }
}
