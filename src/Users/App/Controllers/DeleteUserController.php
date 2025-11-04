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
        #[CurrentUser]
        User $authenticatedUser,
    ): JsonResponse {


        $user->delete();

        return response()->json([
            'message' => 'User account deleted successfully.',
        ], 200);
    }
}
