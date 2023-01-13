<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return redirect(\route('conferences.index'));
});

Route::get('/conferences', [ ConferenceController::class, 'index' ])->name('conferences.index');

Route::group(['middleware' => ['permission:create conference']], function () {
    Route::get('/conferences/create', [ ConferenceController::class, 'create' ])->name('conferences.create');
    Route::post('/conferences', [ ConferenceController::class, 'store' ])->name('conferences.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/conferences/{conference}', [ ConferenceController::class, 'show' ])->name('conferences.show');
    Route::post('/conferences/{conference}/join', [ UserController::class, 'join' ])->name('users.join');
    Route::post('/conferences/{conference}/cancel', [ UserController::class, 'cancel' ])->name('users.cancel');
});


Route::group(['middleware' => ['permission:update conference']], function () {
    Route::get('/conferences/{conference}/edit', [ ConferenceController::class, 'edit' ])->name('conferences.edit');
    Route::patch('/conferences/{conference}', [ ConferenceController::class, 'update' ])->name('conferences.update');
});

Route::group(['middleware' => ['permission:delete conference']], function () {
    Route::delete('/conferences/{conference}', [ ConferenceController::class, 'destroy' ])->name('conferences.destroy');
});


require __DIR__.'/auth.php';
