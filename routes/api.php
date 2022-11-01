<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\CountryController;
use App\Http\Controllers\v1\GameController;
use App\Http\Controllers\v1\LeagueController;
use App\Http\Controllers\v1\SportController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);

        Route::apiResource('users', UserController::class)->only([
            'index',
            'show',
        ]);

        Route::apiResource('countries', CountryController::class)->only([
            'index',
            'show',
        ]);

        Route::apiResource('leagues', LeagueController::class)->only([
            'index',
            'show',
        ]);

        Route::apiResource('sports', SportController::class)->only([
            'index',
            'show',
        ]);

        Route::post('/users/{user}/friends', [UserController::class, 'requestFriend']);
        Route::patch('/users/{user}/friends', [UserController::class, 'answerFriendRequest']);
        Route::post('/users/{user}/prediction', [UserController::class, 'predict']);
        Route::delete('/users/{user}/prediction/{odd}', [UserController::class, 'cancelPrediction']);

        Route::apiResource('games', GameController::class)->only([
            'index',
            'show',
        ]);
    });
});
