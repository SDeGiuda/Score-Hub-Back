<?php

declare(strict_types=1);

namespace Src\MatchResults\Domain\DataTransferObjects;

use Src\MatchResults\Domain\Enums\ResultStatusEnum;

readonly class ResultDto
{
    public function __construct(
        public int $game_id,
        public int $user_id,
        public int $position,
        public int $points,
        public ResultStatusEnum $status,
    ) {}
}
