<?php

declare(strict_types=1);

namespace Src\Games\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Games\App\Requests\UpsertGameRequest;
use Src\Games\Domain\Actions\CreateGameAction;
use Src\Users\App\Resources\UserResource;

class CreateGameControlller
{
    public function __invoke(UpsertGameRequest $request, CreateGameAction $createGameAction): JsonResponse
    {
        $user = $createGameAction->execute($request->toDto());

        return UserResource::make($user)->response();
    }
}
