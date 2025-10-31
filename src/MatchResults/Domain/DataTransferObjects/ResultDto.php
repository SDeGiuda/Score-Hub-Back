<?php

declare(strict_types=1);

namespace Src\MatchResults\Domain\DataTransferObjects;

use Src\MatchResults\Domain\Models\MatchResult;

readonly class ResultDto
{
    public function __construct(
        public MatchResult $matchResult,
        public int $position,
        public int $points,
    ) {
    }
}
