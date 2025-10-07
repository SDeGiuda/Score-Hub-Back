<?php

declare(strict_types=1);

namespace Src\Games\Domain\DataTransferObjects;

class GameDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $rules,
        public readonly int $number_of_players,
        //En los juegos precargados es null.
        public readonly int $way_of_ending_id,
        public readonly int $visibility,
        //1 Publico, 2 Privado
        public readonly int $winner_criteria,
        public readonly string|null $description = null,
        public readonly bool $hasTeams = false,
        public readonly int $number_of_teams = 0,
        public readonly bool $hasTurns = false,
        public readonly int $turn_duration = 0,
        public readonly int $number_of_turns = 0,
        public readonly int|null $time_per_turn = null, //En caso que el juego no tenga turnos, se settea en null. Verificar con Santi.
        public readonly int|null $image_url_id = null, //En los juegos precargdos dicho id apuntara a alguna de las imagenes precargadas.
        public readonly int|null $created_by_user_id = null, //Id:1 para puntos, Id:2 para tiempo, Id:3 para rondas.
        public readonly int|null $time_to_end = null,
        public readonly int|null $points_to_end = null, //Ejemplos para ganar truco, para perder conga
        public readonly int|null $rounds_to_end = null, //1 Mayor puntaje, 2 Menor puntaje, 3 jugador en llegar mas cerca de X puntos
        public readonly int|null $created_at = null,
        public readonly int|null $updated_at = null,
    ) {
    }
}
