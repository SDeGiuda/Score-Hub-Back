<?php

namespace Src\Games\Domain;

enum EndingEnum:string
{
    case END_ROUNDS = 'end_rounds';
    case REACH_MAX_SCORE = 'reach_max_score';
    case REACH_MIN_SCORE = 'reach_min_score';

}
