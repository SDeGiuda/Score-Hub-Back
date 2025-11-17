<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Src\Users\App\Requests\ForgotPasswordRequest;

final readonly class ForgotPasswordController
{
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $email = $request->validated('email');

        try {
            // Send password reset link
            $status = Password::sendResetLink(
                ['email' => $email]
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => 'Password reset link sent to your email address.',
                ], 200);
            }
        } catch (\Exception) {
        }

        return response()->json([
            'error' => 'failed_to_send',
            'message' => 'Unable to send password reset link. Please try again.',
        ], 500);
    }
}
