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
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult query()
 *
 * @property int                          $id
 * @property int                          $creator_id
 * @property int                          $game_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class MatchResult extends Model
{
    protected $table = 'matches';

    protected $guarded = [];

    /**
     * @return BelongsTo<User, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Game, $this>
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
