<?php

declare(strict_types=1);

namespace Src\Games\App\Controllers;

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Src\Games\App\Requests\UpsertGameRequest;
use Src\Games\App\Resources\GameResource;
use Src\Games\Domain\Actions\CreateGameAction;
use Src\Users\Domain\Models\User;

class CreateGameControlller
{
    public function __invoke(
        UpsertGameRequest $request,
        CreateGameAction $createGameAction,
        #[CurrentUser]
        User $user,
    ): JsonResponse {
        $game = $createGameAction->execute($request->toDto(), $user);

        return GameResource::make($game)->response();
    }
}
