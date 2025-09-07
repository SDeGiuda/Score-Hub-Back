<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Users\App\Requests\LoginRequest;
use Src\Users\Domain\Actions\LoginAction;

class LoginController
{
    public function __invoke(
        LoginAction $loginAction,
        LoginRequest $loginRequest,
    ): JsonResponse {
        $credentials = $loginRequest->only([$loginRequest::EMAIL, $loginRequest::PASSWORD]);

        $loginDto = $loginAction->execute($credentials);

        return response()->json([
            'data' => [
                'access_token' => $loginDto->accessToken,
                'token_type' => $loginDto->tokenType,
                'expires_in' => $loginDto->expiresIn,
            ],
        ]);
    }
}
