<?php

declare(strict_types=1);

namespace Src\MatchResults\App\Controllers;

use Illuminate\Container\Attributes\CurrentUser;

use Illuminate\Http\JsonResponse;
use Src\MatchResults\App\Resources\ResultResource;
use Src\MatchResults\Domain\Enums\ResultStatusEnum;
use Src\Users\Domain\Models\User;

class ListResultsController
{
    public function __invoke(#[CurrentUser] User $user): JsonResponse
    {
        $results = $user->results()->where('status', ResultStatusEnum::ACTIVE->value)->paginate(15);

        return ResultResource::collection($results)->response();
    }
}
