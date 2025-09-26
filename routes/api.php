<?php

declare(strict_types=1);

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\Route;
use Src\Users\App\Controllers\{GetUserController, DeleteUserController, ListUserController, SignUpController, UpdateUserController,LoginController};
use Src\Games\App\Controllers\CreateGameControlller;
use Src\Games\App\Controllers\GetGameController;
use Src\Games\App\Controllers\ListGameController;
use Src\Games\App\Controllers\UpdateGameController;

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

Route::middleware('auth:sanctum')
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
Route::prefix('users')
    ->middleware([])
    ->group(static function (): void {
        Route::get('/', ListUserController::class);
        Route::get('/{user}', GetUserController::class)
            ->withTrashed()
            ->whereNumber('user');
        Route::post('/', SignUpController::class);
        Route::put('/{user}', UpdateUserController::class)
            ->whereNumber('user');
        Route::delete('/{user}', DeleteUserController::class)
            ->whereNumber('user');
        Route::post('/login', LoginController::class);
    });

Route::middleware(['auth'])->group(static function (): void {
    Route::prefix("/games")->group(static function (): void {
        Route::get('/', ListGameController::class);
        Route::get('/{game}', GetGameController::class);
        Route::post('/',CreateGameControlller::class);
        Route::put('/{game}', UpdateGameController::class);
        // Route::delete('/{game}', DeleteGameController::class); Capaz que un usuario admin pueda eliminar un juego.
    });
});
