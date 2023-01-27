<?php

use App\Http\Controllers\CountryController;
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

Route::get(
    '/',
    function () {
        return redirect(\route('conferences.index'));
    }
);

Route::get('/conferences', [ ConferenceController::class, 'index' ])->name('conferences.index');
Route::get('/countries', [ CountryController::class, 'index' ])->name('countries.index');

Route::group(
    ['middleware' => ['permission:create conference']],
    function () {
        Route::post('/conferences', [ ConferenceController::class, 'store' ])->name('conferences.store');
    }
);

Route::middleware('auth')->group(
    function () {
        Route::get('user/{user}', [UserController::class, 'getUser']);
        Route::get('/conferences/{conference}', [ ConferenceController::class, 'show' ])->name('conferences.show');
        }
);

Route::middleware(['auth', 'role:Announcer|Listener'])->group(
    function () {
        Route::post('/conferences/{conference}/join', [ UserController::class, 'join' ])->name('users.join');
        Route::post('/conferences/{conference}/cancel', [ UserController::class, 'cancel' ])->name('users.cancel');
    }
);


Route::group(
    ['middleware' => ['permission:update conference', 'creator']],
    function () {
        Route::patch('/conferences/{conference}', [ ConferenceController::class, 'update' ])->name('conferences.update');
    }
);

Route::group(
    ['middleware' => ['permission:delete conference', 'creator']],
    function () {
        Route::delete('/conferences/{conference}', [ ConferenceController::class, 'destroy' ])->name('conferences.destroy');
    }
);


require __DIR__.'/auth.php';
