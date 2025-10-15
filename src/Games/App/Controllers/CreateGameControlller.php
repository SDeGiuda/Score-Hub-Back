<?php

declare(strict_types=1);

namespace Src\Games\App\Controllers;

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Src\Games\App\Requests\UpsertGameRequest;
use Src\Games\Domain\Actions\CreateGameAction;
use Src\Users\App\Resources\UserResource;
use Src\Users\Domain\Models\User;

class CreateGameControlller
{
    public function __invoke(
        UpsertGameRequest $request,
        CreateGameAction $createGameAction,
        #[CurrentUser]
        User $user,
    ): JsonResponse {
        $user = $createGameAction->execute($request->toDto(), $user);

        return UserResource::make($user)->response();
    }
}
