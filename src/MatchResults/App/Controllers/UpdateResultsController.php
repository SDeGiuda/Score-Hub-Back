<?php

declare(strict_types=1);

namespace Src\MatchResults\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Src\MatchResults\App\Requests\UpdateResultsRequest;
use Src\MatchResults\App\Resources\ResultResource;
use Src\MatchResults\Domain\DataTransferObjects\ResultDto;
use Src\MatchResults\Domain\Enums\ResultStatusEnum;

final readonly class UpdateResultsController
{
    public function __invoke(UpdateResultsRequest $request): JsonResponse
    {
        /** @var Collection<int, ResultDto> $dtos */
        $dtos = collect($request->toDtos());

        DB::transaction(function () use ($dtos): void {
            $dtos->each(function (ResultDto $dto): void {
                $dto->matchResult->updateOrFail(
                    [
                        'position' => $dto->position,
                        'points' => $dto->points,
                        'status' => ResultStatusEnum::ACTIVE->value,
                    ]
                );
            });
        });

        return ResultResource::collection($dtos->pluck('matchResult'))->response();
    }
}
