<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Src\Users\Domain\Models\User;

final readonly class GetUserStatsController
{
    public function __invoke(#[CurrentUser] User $user): JsonResponse
    {
        try {
            // Get total matches played
            $totalMatches = DB::table('match_results')
                ->where('user_id', $user->id)
                ->distinct('match_id')
                ->count('match_id');

            // Get victories (position = 1)
            $victories = DB::table('match_results')
                ->where('user_id', $user->id)
                ->where('position', 1)
                ->count();

            // Get defeats (position > 1)
            $defeats = DB::table('match_results')
                ->where('user_id', $user->id)
                ->where('position', '>', 1)
                ->count();

            // Calculate current streak (consecutive wins from most recent matches)
            $recentResults = DB::table('match_results')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->pluck('position');

            $currentStreak = 0;
            foreach ($recentResults as $position) {
                if ($position === 1) {
                    $currentStreak++;
                } else {
                    break;
                }
            }

            // Get most played games
            $favoriteGames = DB::table('match_results')
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
                ->limit(5)
                ->get();

            // Get recent matches
            $recentMatches = DB::table('match_results')
                ->join('matches', 'match_results.match_id', '=', 'matches.id')
                ->join('games', 'matches.game_id', '=', 'games.id')
                ->leftJoin('users as winners', function ($join): void {
                    $join->on('winners.id', '=', DB::raw('(
                        SELECT user_id FROM match_results
                        WHERE match_id = match_results.match_id
                        AND position = 1
                        LIMIT 1
                    )'));
                })
                ->where('match_results.user_id', $user->id)
                ->select(
                    'matches.id',
                    'matches.name as match_name',
                    'games.name as game_name',
                    'games.icon',
                    'games.color',
                    'match_results.position',
                    'winners.name as winner_name',
                    'matches.created_at'
                )
                ->orderBy('matches.created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($match): array {
                    return [
                        'id' => $match->id,
                        'match_name' => $match->match_name,
                        'game_name' => $match->game_name,
                        'icon' => $match->icon,
                        'color' => $match->color,
                        'position' => $match->position,
                        'winner' => $match->winner_name,
                        'date' => $match->created_at,
                        'won' => $match->position === 1,
                    ];
                });

            // Calculate win rate
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
            ], 500);
        }
    }
}
