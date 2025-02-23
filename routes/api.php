<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Backstage\GameController;
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

Route::post('/flip', [ApiController::class, 'flip'])->name('api.flip');
Route::get('/start', [GameController::class, 'startOrResumeGame'])->name('game.start');
