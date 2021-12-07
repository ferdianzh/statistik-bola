<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API;
use App\Http\Controllers\API\AuthController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResources([
        'team' => API\TeamController::class,
        'player' => API\PlayerController::class,
        'site' => API\SiteController::class,
        'match' => API\MatchController::class,
    ]);
    Route::get('player/team/{team_id}', [API\PlayerController::class, 'showByTeam']);
    Route::get('match/{id}/detail', [API\MatchController::class, 'showDetailed']);
    Route::get('match/team/{id}', [API\MatchController::class, 'showDetailedByTeam']);

    Route::prefix('stat')->group(function () {
        Route::apiResources([
            'match' => API\MatchStatController::class,
            'player' => API\MatchPlayerStatController::class,
            'goal' => API\MatchGoalController::class,
            'card' => API\MatchCardController::class,
        ]);
        Route::get('player/match/{id}', [API\MatchPlayerStatController::class, 'showByMatch']);
    });
});
