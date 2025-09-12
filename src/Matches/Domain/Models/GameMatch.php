<?php

declare(strict_types=1);

namespace Src\Match\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Games\Domain\Models\Game;
use Src\MatchResults\Domain\Models\MatchResult;

class GameMatch extends Model
{
    protected $table = "matches";
    protected $guarded = [];

    /**
     * @return BelongsTo<Game,$this>
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * @return HasMany<MatchResult,$this>
     */
    public function results(): HasMany{
        return $this->hasMany(MatchResult::class);
    }


}
