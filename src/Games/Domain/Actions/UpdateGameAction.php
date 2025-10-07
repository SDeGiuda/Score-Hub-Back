<?php

declare(strict_types=1);

namespace Src\Games\Domain\Actions;

use Illuminate\Validation\ValidationException;
use Src\Games\Domain\DataTransferObjects\GameDto;
use Src\Games\Domain\Models\Game;

class UpdateGameAction
{
    public function execute(Game $game, GameDto $gameDto): Game
    {
        if ($gameDto->hasTeams && ! $gameDto->number_of_teams > 1) {
            throw ValidationException::withMessages([]);
        }
        $game->update([
            'name' => $gameDto->name,
            'number_of_players'=> $gameDto->number_of_players,
            'turn_duration'=>  $gameDto->turn_duration,
            'hasTurns'=>   $gameDto->hasTurns,
            'hasTeams'=> $gameDto->hasTeams,
            'number_of_teams'=>$gameDto->number_of_teams,
            'rules'=>$gameDto->rules,
        ]);

        return $game;
    }
}
