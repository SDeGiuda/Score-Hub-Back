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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'number_of_players'=> $this->number_of_players,
            'turn_duration'=>  $this->turn_duration,
            'round_duration'=>  $this->round_duration,
            'rounds'=> $this->rounds,
            'starting_points'=> $this->starting_points,
            'finishing_points'=> $this->finishing_points,
            'is_winning'=> $this->is_winning,
            'has_turns'=>   $this->has_turns,
            'has_teams'=> $this->has_teams,
            'min_team_length'=>$this->min_team_length,
            'max_team_length'=>$this->max_team_length,
            'rules'=>$this->rules,
            'icon' => $this->icon,
            'color' => $this->color,
            'bg_color' => $this->bg_color,
            'user_id' => $this->user_id,
            'creator'=> User::find($this->user_id)->name ?? 'legacy',
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
