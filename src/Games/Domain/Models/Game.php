<?php

declare(strict_types=1);

namespace Src\Games\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Matches\Domain\Models\GameMatch;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game query()
 * @property int                          $id
 * @property string                       $name
 * @property int                          $number_of_players
 * @property int                          $turn_duration
 * @property int                          $round_duration
 * @property bool                         $has_turns
 * @property bool                         $has_teams
 * @property int                          $team_length
 * @property string|null                  $rules
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereHasTeams($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereHasTurns($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereNumberOfPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereRoundDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereRules($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereTeamLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereTurnDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, GameMatch> $matches
 * @property-read int|null $matches_count
 * @property int    $rounds
 * @property string $ending
 * @property int    $min_team_length
 * @property int    $max_team_length
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereEnding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereMaxTeamLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereMinTeamLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereRounds($value)
 * @property int $min_points
 * @property int $max_points
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereMaxPoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereMinPoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereUserId($value)
 * @property int         $starting_points
 * @property int         $finishing_points
 * @property string|null $description
 * @property string      $icon
 * @property string      $color
 * @property string      $bg_color
 * @property bool        $is_winning
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereBgColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereFinishingPoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereIsWinning($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereStartingPoints($value)
 * @mixin \Eloquent
 */
class Game extends Model
{
    public $table = 'games';

    protected $guarded = [];

    /**
     * @return HasMany<GameMatch, $this>
     */
    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class);
    }
}
