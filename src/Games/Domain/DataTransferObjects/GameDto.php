<?php

declare(strict_types=1);

namespace Src\Games\Domain\DataTransferObjects;

use Src\Games\Domain\EndingEnum;

class GameDto
{
    public function __construct(
        public readonly string $name,
        public readonly int $number_of_players,
        public readonly int $turn_duration,
        public readonly int $round_duration,
        public readonly int $rounds,
        public readonly EndingEnum $ending,
        public readonly bool $hasTurns,
        public readonly bool $hasTeams,
        public readonly int $min_team_length,
        public readonly int $max_team_length,
        public readonly string $rules,
        public readonly int $min_points,
        public readonly int $max_points,
        public readonly string $color,
        public readonly string $icon,
    ) {
    }
}
