<?php

declare(strict_types=1);

namespace Src\Matches\App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Games\App\Resources\GameResource;
use Src\Matches\Domain\Models\GameMatch;
use Src\MatchResults\App\Resources\ResultResource;
use Src\Users\App\Resources\UserResource;

/**
 * @mixin GameMatch
 */
class GameMatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'game_id' => $this->game_id,
            'creator_id' => $this->creator_id,
            'game' => new GameResource($this->whenLoaded('game')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'results' => ResultResource::collection($this->whenLoaded('results')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
