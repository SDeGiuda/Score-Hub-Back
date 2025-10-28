<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Src\Users\Domain\Models\User;

final readonly class DeleteUserController
{
    public function __invoke(
        User $user,
        #[CurrentUser] User $authenticatedUser
    ): JsonResponse {
        // Authorization: Users can only delete their own account
        if ($authenticatedUser->id !== $user->id) {
            return response()->json([
                'error' => 'forbidden',
                'message' => 'You are not authorized to delete this user account.',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User account deleted successfully.',
        ], 200);
    }
}
