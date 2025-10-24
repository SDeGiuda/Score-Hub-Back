<?php

declare(strict_types=1);

namespace Src\Games\Domain\DataTransferObjects;

class GameDto
{
    public function __construct(
        public readonly string $name,
        public readonly int $number_of_players,
        public readonly int $turn_duration,
        public readonly int $round_duration,
        public readonly int $rounds,
        public readonly bool $hasTurns,
        public readonly bool $hasTeams,
        public readonly int $min_team_length,
        public readonly int $max_team_length,
        public readonly string $rules,
        public readonly int $starting_points,
        public readonly int $finishing_points,
        public readonly bool $is_winning,
        public readonly string $color,
        public readonly string $bgColor,
        public readonly string $icon,
        public readonly string $description,
    ) {
    }
}
