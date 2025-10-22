<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Users\App\Requests\UpsertUserRequest;
use Src\Users\App\Resources\UserResource;
use Src\Users\Domain\Actions\SignUpAction;
use Src\Users\Domain\Actions\LoginAction;

final readonly class SignUpController
{
    public function __invoke(UpsertUserRequest $request, SignUpAction $storeUserAction, LoginAction $loginAction): JsonResponse
    {
        $dto = $request->toDto();
        $user = $storeUserAction->execute($dto);
        $loginDto = $loginAction->execute(['email' => $dto->emailAddress, 'password' => $dto->password]);

        return response()->json([
            'data' => [
                'access_token' => $loginDto->accessToken,
                'token_type' => $loginDto->tokenType,
                'expires_in' => $loginDto->expiresIn,
            ],
        ]);
    }
}
