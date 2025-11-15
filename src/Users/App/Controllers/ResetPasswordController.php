<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Users\App\Requests\ResetPasswordRequest;
use Src\Users\App\Resources\UserResource;
use Src\Users\Domain\Models\User;

final readonly class ResetPasswordController
{
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        $user = User::query()->where('email', $dto->email)->firstOrFail();
        $user->password = $dto->password;
        $user->save();

        return UserResource::make($user)->response();
    }
}
