<?php

declare(strict_types=1);

namespace Src\MatchResults\Domain\Enums;

enum ResultStatusEnum: string
{
    case PENDING = 'pending';
    case ACTIVE  = 'active';
}
