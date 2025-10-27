<?php

declare(strict_types=1);

namespace Src\MatchResults\App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\MatchResults\Domain\Models\MatchResult;

/**
 * @mixin MatchResult
 */
class ResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
          'id' => $this->id,
          'user_id' => $this->user_id,
          'match_id' => $this->match_id,
          'position' => $this->position,
          'created_at' => $this->created_at,
        ];
    }
}
