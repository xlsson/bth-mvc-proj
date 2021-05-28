<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\YatzyController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [SessionController::class, 'start']);
Route::get('/login', [SessionController::class, 'start']);
Route::post('/login', [SessionController::class, 'verifyCreate']);
Route::get('/logout', [SessionController::class, 'logoutDestroy']);

Route::get('/register', [AccountController::class, 'start']);
Route::post('/register', [AccountController::class, 'verifySave']);
Route::post('/myaccount', [AccountController::class, 'denyChallenge']);
Route::get('/myaccount', [AccountController::class, 'myAccount'])->name('myaccount');

Route::get('/gamemode', [YatzyController::class, 'gamemode']);
Route::post('/yatzysetup', [YatzyController::class, 'setup']);
Route::post('/yatzyplay', [YatzyController::class, 'play']);
Route::get('/yatzyview', [YatzyController::class, 'yatzyview'])->name('yatzyview');

Route::post('/highscores', [ResultsController::class, 'submitResult']);
Route::get('/highscores', [ResultsController::class, 'highScores'])->name('highscores');

Route::get('/result/{id}', function ($id) {
    $ctrl = new ResultsController();
    return $ctrl->oneResult($id);
});

Route::get('/challenge/{id}', function ($id) {
    $ctrl = new ResultsController();
    return $ctrl->oneChallenge($id);
})->name('challenge');
