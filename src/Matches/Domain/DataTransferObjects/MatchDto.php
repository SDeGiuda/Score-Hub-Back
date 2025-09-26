<?php

declare(strict_types=1);

namespace Src\Matches\Domain\DataTransferObjects;

readonly class MatchDto
{
    public function __construct(
        public int $creatorId,
        public int $gameId,
    ) {
    }
}
