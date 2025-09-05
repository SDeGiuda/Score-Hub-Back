<?php

declare(strict_types=1);

namespace Src\Security\Domain\Actions;

class PreventDebugInProductionAction
{
    public static function execute(
        bool $isProduction,
        bool $isDebug,
    ): void {
        if ($isProduction && $isDebug) {
            throw new \Exception('Debug mode is enabled in production environment');
        }
    }
}
