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

            $totalMatches = $user->results_count;


            $victories = MatchResult::query()
                ->where('user_id', $user->id)
                ->where('position', 1)
                ->count();

            $defeats = $totalMatches - $victories;

            $recentResults =$user->results()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->pluck('position');

            $currentStreak = $recentResults->takeWhile(fn ($value) => $value==1)->count();

            // Get most played games
            $favoriteGames =$user->matches()
                ->selectRaw('matches.game_id, COUNT(*) as count')
                ->groupBy('matches.game_id')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'game_id');

            $recentMatches = $user->matches()
                ->with(['results','game'])
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($match) use ($user): array {
                    $result = $match->results->where('user_id', $user->id)->first();
                    return [
                        'id' => $match->id,
                        'match_name' => $match->name,
                        'game_name' => $match->game->name,
                        'icon' => $match->game->icon,
                        'color' => $match->game->color,
                        'position' => $result->position,
                        'date' => $match->created_at,
                        'won' => $result->position === 1,
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
                'message' => "error",
            ], 500);
        }
    }
}
