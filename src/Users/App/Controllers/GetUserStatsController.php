<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Src\MatchResults\Domain\Models\MatchResult;
use Src\Users\Domain\Models\User;

final readonly class GetUserStatsController
{
    public function __invoke(#[CurrentUser] User $user): JsonResponse
    {
        try {
            // Calculate total matches directly from results table
            $totalMatches = MatchResult::query()
                ->where('user_id', $user->id)
                ->count();

            $victories = MatchResult::query()
                ->where('user_id', $user->id)
                ->where('position', 1)
                ->count();

            $defeats = $totalMatches - $victories;

            $recentResults = $user->results()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->pluck('position');

            $currentStreak = $recentResults->takeWhile(fn ($value) => $value == 1)->count();

            // Get most played games with game details
            $favoriteGamesData = DB::table('match_results')
                ->join('matches', 'match_results.match_id', '=', 'matches.id')
                ->join('games', 'matches.game_id', '=', 'games.id')
                ->where('match_results.user_id', $user->id)
                ->select(
                    'games.id',
                    'games.name',
                    'games.icon',
                    'games.color',
                    'games.bg_color',
                    DB::raw('COUNT(DISTINCT matches.id) as matches_count')
                )
                ->groupBy('games.id', 'games.name', 'games.icon', 'games.color', 'games.bg_color')
                ->orderByDesc('matches_count')
                ->limit(10)
                ->get();

            $favoriteGames = $favoriteGamesData->map(function ($game): array {
                return [
                    'id' => $game->id,
                    'name' => $game->name,
                    'icon' => $game->icon,
                    'color' => $game->color,
                    'bg_color' => $game->bg_color,
                    'matches_count' => $game->matches_count,
                ];
            });

            $recentMatches = $user->matches()
                ->with(['results.player', 'game'])
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($match) use ($user): array {
                    $result = $match->results->where('user_id', $user->id)->first();
                    $winner = $match->results->where('position', 1)->first();

                    if ($result === null) {
                        $position = 0;
                    } else {
                        $position = $result->position;
                    }

                    return [
                        'id' => $match->id,
                        'match_name' => $match->name,
                        'game_name' => $match->game->name,
                        'icon' => $match->game->icon,
                        'color' => $match->game->color,
                        'position' => $position,
                        'winner' => $winner->player->name ?? 'N/A',
                        'date' => $match->created_at,
                        'won' => $position === 1,
                    ];
                });

            $winRate = $totalMatches > 0 ? round(($victories / $totalMatches) * 100, 1) : 0;

            return response()->json([
                'data' => [
                    'total_matches' => $totalMatches,
                    'victories' => $victories,
                    'defeats' => $defeats,
                    'current_streak' => $currentStreak,
                    'win_rate' => $winRate,
                    'favorite_games' => $favoriteGames,
                    'recent_matches' => $recentMatches,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch user statistics',
                'message' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null,
            ], 500);
        }
    }
}
