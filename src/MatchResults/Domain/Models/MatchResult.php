<?php

declare(strict_types=1);

namespace Src\MatchResults\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Games\Domain\Models\Game;
use Src\Matches\Domain\Models\GameMatch;
use Src\Users\Domain\Models\User;

/**
 * @property-read Game|null $game
 * @property-read User|null $player
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult query()
 * @property int                          $id
 * @property int                          $user_id
 * @property int                          $match_id
 * @property int                          $position
 * @property string                       $status
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult whereMatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchResult whereUserId($value)
 * @property-read Game $match
 * @mixin \Eloquent
 */
class MatchResult extends Model
{
    protected $table = 'match_results';

    protected $guarded = [];

    /**
     * @return BelongsTo<User, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<GameMatch, $this>
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class);
    }
}
