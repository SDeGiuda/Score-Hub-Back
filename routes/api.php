<?php

declare(strict_types=1);

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\Route;
use Src\Users\App\Controllers\{GetUserController, DeleteUserController, ListUserController, SignUpController, UpdateUserController, LoginController, LogoutController, GetUserStatsController, ForgotPasswordController, ResetPasswordController};
use Src\Games\App\Controllers\CreateGameController;
use Src\Games\App\Controllers\GetGameController;
use Src\Games\App\Controllers\ListGameController;
use Src\Games\App\Controllers\UpdateGameController;
use Src\Matches\App\Controllers\CreateMatchController;
use Src\Matches\App\Controllers\GetMatchController;
use Src\Matches\App\Controllers\DeleteMatchController;
use Src\MatchResults\App\Controllers\ListResultsController;
use Src\MatchResults\App\Controllers\UpdateResultsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')
    ->get('/me', function (#[CurrentUser] $user) {
        return response()->json([
            'data' => $user,
        ]);
    });

/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
*/
Route::post('users/login', LoginController::class);
Route::post('users/', SignUpController::class);
Route::post('users/forgot-password', ForgotPasswordController::class);
Route::post('users/reset-password', ResetPasswordController::class);

Route::prefix('users')
    ->middleware(['auth:api'])
    ->group(static function (): void {
        Route::post('/logout', LogoutController::class);
        Route::get('/stats', GetUserStatsController::class);
        Route::get('/', ListUserController::class);
        Route::get('/{user}', GetUserController::class);
        Route::put('/{user}', UpdateUserController::class);
        Route::delete('/{user}', DeleteUserController::class);
    });

Route::middleware(['auth:api'])->group(static function (): void {
    Route::prefix("/games")->group(static function (): void {
        Route::get('/', ListGameController::class);
        Route::get('/{game}', GetGameController::class);
        Route::post('/', CreateGameController::class);
        Route::put('/{game}', UpdateGameController::class);
    });
    Route::prefix("/game-match")->group(static function (): void {
        Route::post('/', CreateMatchController::class);
        Route::get('/{gameMatch}', GetMatchController::class);
        Route::delete('/{gameMatch}', DeleteMatchController::class);
    });
    Route::prefix("/results")->group(static function (): void {
        Route::patch('/',UpdateResultsController::class);
        Route::get('/', ListResultsController::class);
    });
});
