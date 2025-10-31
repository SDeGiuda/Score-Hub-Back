<?php

declare(strict_types=1);

namespace Src\MatchResults\App\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Src\MatchResults\App\Requests\UpdateResultsRequest;
use Src\MatchResults\App\Resources\ResultResource;
use Src\MatchResults\Domain\Enums\ResultStatusEnum;
use Src\MatchResults\Domain\Models\MatchResult;

final readonly class UpdateResultsController
{
    public function __invoke(UpdateResultsRequest $request): JsonResponse
    {
        /** @var Collection<int, MatchResult> $dtos */
        $dtos = collect($request->toDtos());

        DB::transaction(function () use ($dtos): void {
            $dtos->each(function ($dto): void {
                $dto->matchResult->updateOrFail(
                    [
                        'position' => $dto->position,
                        'points' => $dto->points,
                        'status' => ResultStatusEnum::ACTIVE->value,
                    ]
                );
            });
        });

        return ResultResource::collection($dtos)->response();
    }
}
