<?php

declare(strict_types=1);

namespace Src\Games\Domain\Actions;

use Src\Games\Domain\Models\Game;

class DeleteGameAction
{
    public function execute(Game $game): bool
    {
        return $game->delete();
    }
}
