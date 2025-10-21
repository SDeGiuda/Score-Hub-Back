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
        if ($gameDto->hasTeams && ! $gameDto->min_team_length > 1) {
            throw ValidationException::withMessages([]);
        }
        $game->update([
            'name' => $gameDto->name,
            'number_of_players'=> $gameDto->number_of_players,
            'turn_duration'=>  $gameDto->turn_duration,
            'round_duration' =>  $gameDto->round_duration,
            'rounds'=>  $gameDto->rounds,
            'has_turns'=>   $gameDto->hasTurns,
            'has_teams'=> $gameDto->hasTeams,
            'min_team_length'=>$gameDto->min_team_length,
            'max_team_length'=>$gameDto->max_team_length,
            'ending' => $gameDto->ending->value,
            'rules'=>$gameDto->rules,
            'min_points'=>$gameDto->min_points,
            'max_points'=>$gameDto->max_points,
            'icon'=>$gameDto->icon,
            'color'=>$gameDto->color,
        ]);

        return $game;
    }
}
