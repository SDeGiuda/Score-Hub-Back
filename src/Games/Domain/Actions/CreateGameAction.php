<?php

declare(strict_types=1);

namespace Src\Games\Domain\Actions;

use Illuminate\Validation\ValidationException;
use Src\Games\Domain\DataTransferObjects\GameDto;
use Src\Games\Domain\Models\Game;
use Src\Users\Domain\Models\User;

class CreateGameAction
{
    public function execute(GameDto $gameDto, User $user): Game
    {
        if (! $user) {
            throw ValidationException::withMessages([
                'user' => 'This game was created by a null user.',
            ]);
        }
        if (Game::where('name', $gameDto->name)->exists()) {
            throw ValidationException::withMessages([
                'name' => 'This game name already exists.',
            ]);
        }

        if (
            $gameDto->hasTeams
            && (
                $gameDto->min_team_length < 1
                || $gameDto->max_team_length < $gameDto->min_team_length
            )
        ) {
            throw ValidationException::withMessages([
                'team_length' => 'Team configuration is invalid.',
            ]);
        }

        return Game::create([
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
            'user_id'=>$user->id,
        ]);
    }
}
