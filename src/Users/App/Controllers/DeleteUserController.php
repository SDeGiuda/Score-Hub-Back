<?php

declare(strict_types=1);

namespace Lightit\Users\App\Controllers;

use Illuminate\Http\JsonResponse;
use Lightit\Users\Domain\Models\User;

final readonly class DeleteUserController
{
    public function __invoke(User $user): JsonResponse
    {
        $user->delete();

        return response()->json();
    }
}
