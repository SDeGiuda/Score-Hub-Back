<?php

declare(strict_types=1);

namespace Src\Games\App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Games\Domain\Models\Game;

/**
 * @mixin Game
 */
class GameResource extends JsonResource
{
    /**
     * @return array{name: mixed, number_of_players: mixed, turn_duration: mixed, hasTurns: mixed, hasTeams: mixed, team_length: mixed, rules: mixed}
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'number_of_players'=> $this->number_of_players,
            'turn_duration'=>  $this->turn_duration,
            'hasTurns'=>   $this->has_turns,
            'hasTeams'=> $this->has_teams,
            'team_length'=>$this->team_length,
            'rules'=>$this->rules,
        ];
    }
}
