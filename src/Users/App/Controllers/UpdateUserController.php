<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Users\App\Requests\UpsertUserRequest;
use Src\Users\App\Resources\UserResource;
use Src\Users\Domain\Actions\UpdateUserAction;
use Src\Users\Domain\Models\User;

final readonly class UpdateUserController
{
    public function __invoke(User $user, UpsertUserRequest $request, UpdateUserAction $updateUserAction): JsonResponse
    {
        $user = $updateUserAction->execute($user, $request->toDto());

        return UserResource::make($user)
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }
}
