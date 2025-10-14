<?php

namespace Src\MatchResults\Domain\Actions;

use Src\MatchResults\App\Requests\StoreResultsRequest;
use Src\MatchResults\Domain\DataTransferObjects\ResultDto;
use Src\MatchResults\Domain\Models\MatchResult;

class UpsertResultsAction
{

    /**
     * @param array<int,ResultDto> $resultsDto
     * @return void
     */
    public function execute(array $resultsDto){

        foreach ($resultsDto as $resultDto){
            MatchResult::updateOrCreate([
                'id' => $resultDto->id,
                'match_id' => $resultDto->match_id,
                'user_id' => $resultDto->user_id,
                'position' => $resultDto->position,
                'status' => $resultDto->status,
            ]);
        }

    }
}
