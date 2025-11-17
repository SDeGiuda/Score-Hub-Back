<?php

declare(strict_types=1);

namespace Src\MatchResults\Domain\Actions;

use Sentry\Transport\ResultStatus;
use Src\MatchResults\Domain\DataTransferObjects\ResultDto;
use Src\MatchResults\Domain\Enums\ResultStatusEnum;
use Src\MatchResults\Domain\Models\MatchResult;

class UpsertResultsAction
{
    /**
     * @param array<int, ResultDto> $resultsDto
     */
    public function execute(array $resultsDto): void
    {
        foreach ($resultsDto as $resultDto) {
            $resultDto->matchResult->updateOrFail([
                'position' => $resultDto->position,
                'points' => $resultDto->points,
                'status' => ResultStatusEnum::ACTIVE
            ]);
        }
    }
}
