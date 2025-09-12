<?php

declare(strict_types=1);

namespace Src\Games\Domain\Actions;

use Illuminate\Validation\ValidationException;
use Src\Games\Domain\DataTransferObjects\GameDto;
use Src\Games\Domain\Models\Game;

class CreateGameAction
{
    public function execute(GameDto $gameDto):Game
    {
        if (Game::where('name', $gameDto->name)->exists()) {
            throw ValidationException::withMessages([
                'id' => 'This game ID already exists.',
            ]);
        }

        if($gameDto->hasTeams && !$gameDto->team_length >1){
            throw ValidationException::withMessages([]);
        }

        return Game::create([
            "name" => $gameDto->name,
            "number_of_players"=> $gameDto->number_of_players ,
            "turn_duration"=>  $gameDto->turn_duration,
            "hasTurns"=>   $gameDto->hasTurns,
            "hasTeams"=> $gameDto->hasTeams,
            "team_length"=>$gameDto->team_length,
            "rules"=>$gameDto->rules
        ]);
    }

}
