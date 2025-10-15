<?php

declare(strict_types=1);

namespace Src\Games\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Src\Games\App\Resources\GameResource;
use Src\Games\Domain\Models\Game;

class ListGameController
{
    public function __invoke(): JsonResponse
    {
        try {
            $games = GameResource::collection(Game::all())->response();

            return $games;
        } catch (\Throwable $e) {
            Log::error('Error al listar juegos', [
                'exception' => $e,
            ]);

            return response()->json([
                'message' => 'No se pudieron obtener los juegos.',
            ], 500);
        }
    }
}
