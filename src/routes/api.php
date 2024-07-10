<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TeamController;
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

// Public accessible API
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Authenticated only API
// We use auth api here as a middleware so only authenticated user who can access the endpoint
// We use group so we can apply middleware auth api to all the routes within the group
Route::middleware('auth:api')->group(function () {
    Route::get('/info', [UserController::class, 'info']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/update-info', [UserController::class, 'updateInfo']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    Route::group(['prefix' => 'team', 'as' => 'team.'], function () {
        Route::get('list', [TeamController::class, 'getList']);
        Route::post('create', [TeamController::class, 'create']);
        //Route::post('delete', [TeamController::class, 'delete']);
        Route::post('leave', [TeamController::class, 'leaveTeam']);
        Route::post('join', [TeamController::class, 'joinTeam']);
    });

});
