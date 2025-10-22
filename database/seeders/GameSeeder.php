<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Factories\GameFactory;
use Illuminate\Database\Seeder;
use Src\Games\Domain\EndingEnum;
use Src\Games\Domain\Models\Game;
use Src\Users\Domain\Models\User;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'admin')->first();
        $users = User::all();

        // Create predefined popular games
        $games = [
            [
                'name' => 'UNO',
                'number_of_players' => 10,
                'turn_duration' => 60,
                'round_duration' => 30,
                'rounds' => 5,
                'ending' => EndingEnum::ReachMaxScore->value,
                'has_turns' => true,
                'has_teams' => false,
                'min_team_length' => 1,
                'max_team_length' => 1,
                'rules' => 'El objetivo es ser el primero en deshacerse de todas tus cartas. Coincide colores o números, y usa cartas especiales para cambiar el juego.',
                'min_points' => 0,
                'max_points' => 500,
                'icon' => '🎴',
                'color' => '#FF0000',
                'bg_color' => '#FFE5E5',
                'description' => 'Juego de cartas clásico donde el objetivo es quedarse sin cartas',
                'user_id' => $admin->id,
            ],
            [
                'name' => 'Truco',
                'number_of_players' => 6,
                'turn_duration' => 120,
                'round_duration' => 15,
                'rounds' => 10,
                'ending' => EndingEnum::ReachMaxScore->value,
                'has_turns' => true,
                'has_teams' => true,
                'min_team_length' => 2,
                'max_team_length' => 3,
                'rules' => 'Juego de cartas argentino donde se juega en equipos. El objetivo es ganar bazas y llegar a 30 puntos.',
                'min_points' => 0,
                'max_points' => 30,
                'icon' => '🃏',
                'color' => '#1E88E5',
                'bg_color' => '#E3F2FD',
                'description' => 'Juego tradicional argentino de cartas y estrategia',
                'user_id' => $admin->id,
            ],
            [
                'name' => 'Generala',
                'number_of_players' => 8,
                'turn_duration' => 90,
                'round_duration' => 20,
                'rounds' => 10,
                'ending' => EndingEnum::EndRounds->value,
                'has_turns' => true,
                'has_teams' => false,
                'min_team_length' => 1,
                'max_team_length' => 1,
                'rules' => 'Juego de dados donde debes completar diferentes combinaciones. Gana quien tenga más puntos al final.',
                'min_points' => 0,
                'max_points' => 100,
                'icon' => '🎲',
                'color' => '#4CAF50',
                'bg_color' => '#E8F5E9',
                'description' => 'Juego de dados con múltiples combinaciones',
                'user_id' => $admin->id,
            ],
            [
                'name' => 'Poker',
                'number_of_players' => 9,
                'turn_duration' => 180,
                'round_duration' => 25,
                'rounds' => 20,
                'ending' => EndingEnum::ReachMinScore->value,
                'has_turns' => true,
                'has_teams' => false,
                'min_team_length' => 1,
                'max_team_length' => 1,
                'rules' => 'Juego de cartas de apuestas. Forma la mejor mano de 5 cartas y apuesta estratégicamente.',
                'min_points' => 0,
                'max_points' => 10000,
                'icon' => '♠️',
                'color' => '#9C27B0',
                'bg_color' => '#F3E5F5',
                'description' => 'Juego de cartas de apuestas y estrategia',
                'user_id' => $admin->id,
            ],
            [
                'name' => 'Monopoly',
                'number_of_players' => 6,
                'turn_duration' => 300,
                'round_duration' => 60,
                'rounds' => 30,
                'ending' => EndingEnum::ReachMinScore->value,
                'has_turns' => true,
                'has_teams' => false,
                'min_team_length' => 1,
                'max_team_length' => 1,
                'rules' => 'Compra propiedades, construye hoteles y lleva a tus oponentes a la bancarrota.',
                'min_points' => 0,
                'max_points' => 100000,
                'icon' => '🏠',
                'color' => '#FF9800',
                'bg_color' => '#FFF3E0',
                'description' => 'Juego de estrategia inmobiliaria',
                'user_id' => $admin->id,
            ],
            [
                'name' => 'Scrabble',
                'number_of_players' => 4,
                'turn_duration' => 180,
                'round_duration' => 40,
                'rounds' => 15,
                'ending' => EndingEnum::ReachMaxScore->value,
                'has_turns' => true,
                'has_teams' => false,
                'min_team_length' => 1,
                'max_team_length' => 2,
                'rules' => 'Forma palabras en el tablero para ganar puntos. Las letras especiales multiplican tu puntaje.',
                'min_points' => 0,
                'max_points' => 500,
                'icon' => '📝',
                'color' => '#795548',
                'bg_color' => '#EFEBE9',
                'description' => 'Juego de palabras y vocabulario',
                'user_id' => $admin->id,
            ],
            [
                'name' => 'TEG',
                'number_of_players' => 6,
                'turn_duration' => 240,
                'round_duration' => 45,
                'rounds' => 25,
                'ending' => EndingEnum::ReachMaxScore->value,
                'has_turns' => true,
                'has_teams' => false,
                'min_team_length' => 1,
                'max_team_length' => 1,
                'rules' => 'Juego de estrategia de conquista mundial. Completa objetivos secretos y domina territorios.',
                'min_points' => 0,
                'max_points' => 100,
                'icon' => '🌍',
                'color' => '#607D8B',
                'bg_color' => '#ECEFF1',
                'description' => 'Juego de estrategia y conquista territorial',
                'user_id' => $admin->id,
            ],
            [
                'name' => 'Dominó',
                'number_of_players' => 4,
                'turn_duration' => 90,
                'round_duration' => 20,
                'rounds' => 10,
                'ending' => EndingEnum::ReachMaxScore->value,
                'has_turns' => true,
                'has_teams' => true,
                'min_team_length' => 2,
                'max_team_length' => 2,
                'rules' => 'Conecta fichas con el mismo número. Gana el primero en colocar todas sus fichas.',
                'min_points' => 0,
                'max_points' => 200,
                'icon' => '🀫',
                'color' => '#000000',
                'bg_color' => '#F5F5F5',
                'description' => 'Juego tradicional de fichas',
                'user_id' => $admin->id,
            ],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }

        // Create random games from other users
        $randomUsers = $users->random(10);
        foreach ($randomUsers as $user) {
            GameFactory::new()->count(rand(1, 3))->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
