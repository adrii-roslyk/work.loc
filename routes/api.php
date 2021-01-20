<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\VacancyController;
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

// Auth routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Authenticated
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResource('user', UserController::class)->except(['store']);
    Route::apiResource('organization', OrganizationController::class);
    Route::apiResource('vacancy', VacancyController::class);

    Route::post('vacancy-book', [VacancyController::class, 'book']);
    Route::post('vacancy-unbook',[VacancyController::class, 'unBook']);

    Route::get('stats/vacancy', [StatsController::class, 'countVacancies']);
    Route::get('stats/organization', [StatsController::class, 'countOrganizations']);
    Route::get('stats/user', [StatsController::class, 'countUsers']);

    Route::get('workers-of-each-vacancy', [UserController::class, 'getWorkersOfEachVacancy']);
    Route::get('workers-of-each-organization', [UserController::class, 'getWorkersOfEachOrganization']);
});









