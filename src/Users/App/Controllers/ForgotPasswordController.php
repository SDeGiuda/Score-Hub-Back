<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

final readonly class ForgotPasswordController
{
    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_failed',
                'message' => 'The email field is required and must be a valid email address.',
                'fields' => $validator->errors(),
            ], 422);
        }

        try {
            // Send password reset link
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => 'Password reset link sent to your email address.',
                ], 200);
            }

            return response()->json([
                'error' => 'failed_to_send',
                'message' => 'Unable to send password reset link. Please try again.',
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'server_error',
                'message' => 'An error occurred while processing your request.',
            ], 500);
        }
    }
}
