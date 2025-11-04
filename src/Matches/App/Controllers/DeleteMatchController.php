<?php

declare(strict_types=1);

namespace Src\Matches\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Matches\Domain\Models\GameMatch;

final readonly class DeleteMatchController
{
    public function __invoke(GameMatch $gameMatch): JsonResponse
    {
        try {
            DB::transaction(function () use ($gameMatch): void {
                // Delete all match results first (due to foreign key)
                $gameMatch->results()->delete();

                // Then delete the match
                $gameMatch->delete();
            });

            return response()->json([
                'message' => 'Match deleted successfully',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to delete match', [
                'match_id' => $gameMatch->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to delete match',
                'message' => "unknown error",
            ], 500);
        }
    }
}
