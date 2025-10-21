<?php

declare(strict_types=1);

namespace Src\MatchResults\Domain\Actions;

use Src\MatchResults\Domain\DataTransferObjects\ResultDto;
use Src\MatchResults\Domain\Models\MatchResult;

class UpsertResultsAction
{
    /**
     * @param array<int, ResultDto> $resultsDto
     */
    public function execute(array $resultsDto): void
    {
        foreach ($resultsDto as $resultDto) {
            MatchResult::updateOrCreate(
                [
                'match_id' => $resultDto->match_id,
                'user_id' => $resultDto->user_id],
                [
                'position' => $resultDto->position,
                'status' => $resultDto->status,
            ]
            );
        }
    }
}
