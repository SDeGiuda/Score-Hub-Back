<?php

declare(strict_types=1);

namespace Src\Games\App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Games\Domain\Models\Game;
use Src\Users\Domain\Models\User;

/**
 * @mixin Game
 */
class GameResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'number_of_players'=> $this->number_of_players,
            'turn_duration'=>  $this->turn_duration,
            'round_duration'=>  $this->round_duration,
            'rounds'=> $this->rounds,
            'ending'=> $this->ending,
            'min_points'=> $this->min_points,
            'max_points'=> $this->max_points,
            'has_turns'=>   $this->has_turns,
            'has_teams'=> $this->has_teams,
            'min_team_length'=>$this->min_team_length,
            'max_team_length'=>$this->max_team_length,
            'rules'=>$this->rules,
            'creator'=> User::find($this->user_id)->name ?? 'legacy',
        ];
    }
}
