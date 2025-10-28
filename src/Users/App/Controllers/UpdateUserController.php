<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Src\Users\App\Requests\UpsertUserRequest;
use Src\Users\App\Resources\UserResource;
use Src\Users\Domain\Actions\UpdateUserAction;
use Src\Users\Domain\Models\User;

final readonly class UpdateUserController
{
    public function __invoke(
        User $user,
        UpsertUserRequest $request,
        UpdateUserAction $updateUserAction,
        #[CurrentUser]
        User $authenticatedUser,
    ): JsonResponse {
        // Authorization: Users can only update their own profile
        if ($authenticatedUser->id !== $user->id) {
            return response()->json([
                'error' => 'forbidden',
                'message' => 'You are not authorized to update this user profile.',
            ], 403);
        }

        $user = $updateUserAction->execute($user, $request->toDto());

        return UserResource::make($user)
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }
}
