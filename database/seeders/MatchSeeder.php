<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Games\Domain\Models\Game;
use Src\Matches\Domain\Models\GameMatch;
use Src\MatchResults\Domain\Enums\ResultStatusEnum;
use Src\MatchResults\Domain\Models\MatchResult;
use Src\Users\Domain\Models\User;

class MatchSeeder extends Seeder
{
    public function run(): void
    {
        $games = Game::all();
        $users = User::all();

        if ($games->isEmpty() || $users->isEmpty()) {
            return;
        }

        // Create matches for each game
        foreach ($games->take(10) as $game) {
            $numberOfMatches = rand(2, 5);

            for ($i = 0; $i < $numberOfMatches; $i++) {
                $creator = $users->random();
                $numberOfPlayers = min($game->number_of_players, rand(2, 6));

                // Create match
                $match = GameMatch::create([
                    'creator_id' => $creator->id,
                    'game_id' => $game->id,
                    'name' => "{$game->name} - Partida " . ($i + 1),
                ]);

                // Get random players for this match (excluding duplicates)
                $players = $users->random($numberOfPlayers);

                // Create match results for each player
                foreach ($players as $index => $player) {
                    $position = $index + 1;

                    MatchResult::create([
                        'match_id' => $match->id,
                        'user_id' => $player->id,
                        'position' => $position,
                        'status' => ResultStatusEnum::ACTIVE->value,
                    ]);
                }
            }
        }

        // Create some ongoing matches (with PENDING status)
        foreach ($games->take(5) as $game) {
            $creator = $users->random();
            $numberOfPlayers = min($game->number_of_players, rand(2, 4));

            $match = GameMatch::create([
                'creator_id' => $creator->id,
                'game_id' => $game->id,
                'name' => "{$game->name} - En curso",
            ]);

            $players = $users->random($numberOfPlayers);

            foreach ($players as $player) {
                MatchResult::create([
                    'match_id' => $match->id,
                    'user_id' => $player->id,
                    'position' => 0,
                    'status' => ResultStatusEnum::PENDING->value,
                ]);
            }
        }
    }
}
