<?php

declare(strict_types=1);

namespace Src\Games\Domain;

enum EndingEnum: string
{
    case EndRounds = 'end_rounds';
    case ReachMaxScore = 'reach_max_score';
    case ReachMinScore = 'reach_min_score';
}
