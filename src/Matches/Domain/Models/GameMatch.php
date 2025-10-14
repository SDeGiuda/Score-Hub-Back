<?php

declare(strict_types=1);

namespace Src\Matches\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Games\Domain\Models\Game;
use Src\MatchResults\Domain\Models\MatchResult;
use Src\Users\Domain\Models\User;

/**
 * @property int                          $id
 * @property int                          $creator_id
 * @property int                          $game_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read User $creator
 * @property-read Game $game
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MatchResult> $results
 * @property-read int|null $results_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereUpdatedAt($value)
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereName($value)
 * @mixin \Eloquent
 */
class GameMatch extends Model
{
    protected $table = 'matches';

    protected $guarded = [];

    /**
     * @return BelongsTo<Game, $this>
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * @return HasMany<MatchResult, $this>
     */
    public function results(): HasMany
    {
        return $this->hasMany(MatchResult::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
