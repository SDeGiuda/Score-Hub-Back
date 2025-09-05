<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Users\App\Resources\UserResource;
use Src\Users\Domain\Actions\ListUserAction;

final readonly class ListUserController
{
    public function __invoke(
        ListUserAction $action,
    ): JsonResponse {
        $users = $action->execute();

        return UserResource::collection($users)
            ->response();
    }
}
