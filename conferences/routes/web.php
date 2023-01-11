<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\ConferenceController;

Route::get('/conferences', [ ConferenceController::class, 'index' ])->name('conferences.index');
Route::get('/conferences/create', [ ConferenceController::class, 'create' ])->name('conferences.create');
Route::post('/conferences', [ ConferenceController::class, 'store' ])->name('conferences.store');
Route::get('/conferences/{conference}', [ ConferenceController::class, 'show' ])->name('conferences.show');
Route::get('/conferences/{conference}/edit', [ ConferenceController::class, 'edit' ])->name('conferences.edit');
Route::patch('/conferences/{conference}', [ ConferenceController::class, 'update' ])->name('conferences.update');
Route::delete('/conferences/{conference}', [ ConferenceController::class, 'destroy' ])->name('conferences.destroy');


