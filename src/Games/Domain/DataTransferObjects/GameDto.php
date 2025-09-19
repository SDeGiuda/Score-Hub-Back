<?php

declare(strict_types=1);

namespace Src\Games\Domain\DataTransferObjects;

class GameDto
{
    public function __construct(
        public readonly string $name,
        public readonly int $number_of_players,
        public readonly int $turn_duration,
        public readonly bool $hasTurns,
        public readonly bool $hasTeams,
        public readonly int $team_length,
        public readonly string $rules,
    ) {
    }
}
