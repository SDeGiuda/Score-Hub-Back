<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Users\App\Requests\UpsertUserRequest;
use Src\Users\App\Resources\UserResource;
use Src\Users\Domain\Actions\SignUpAction;

final readonly class SignUpController
{
    public function __invoke(UpsertUserRequest $request, SignUpAction $storeUserAction): JsonResponse
    {
        $user = $storeUserAction->execute($request->toDto());

        return UserResource::make($user)
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
