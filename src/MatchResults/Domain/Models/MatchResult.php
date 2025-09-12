<?php

declare(strict_types=1);

namespace Src\MatchResults\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Games\Domain\Models\Game;
use Src\Users\Domain\Models\User;

/**
 * @property-read Game|null $game
 * @property-read User|null $player
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult query()
 * @mixin \Eloquent
 */
class MatchResult extends Model
{
    protected $table = "matches";
    protected $guarded = [];

    /**
     * @return BelongsTo<User,$this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * @return BelongsTo<Game,$this>
     */
    public function game(): BelongsTo{
        return $this->belongsTo(Game::class);
    }


}
