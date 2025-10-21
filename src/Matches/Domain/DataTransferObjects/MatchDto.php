<?php

declare(strict_types=1);

namespace Src\Matches\Domain\DataTransferObjects;

readonly class MatchDto
{
    /**
     * @param array<int, string> $players
     */
    public function __construct(
        public int $creatorId,
        public int $gameId,
        public array $players,
    ) {
    }
}
