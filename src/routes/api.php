<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get(
    '/',
    function () {
        return redirect(\route('conferences.index'));
    }
);

Route::get('/conferences', [ ConferenceController::class, 'index' ]);
Route::get('/countries', [ CountryController::class, 'index' ]);

Route::group(
    ['middleware' => ['permission:create conference']],
    function () {
        Route::post('/conferences', [ ConferenceController::class, 'store' ]);
    }
);

Route::middleware('auth')->group(
    function () {
        Route::get('user/{user}', [UserController::class, 'getUser']);
        Route::get('/conferences/{conference}', [ ConferenceController::class, 'show' ]);
        Route::get('/reports', [ ReportController::class, 'index' ]);
        Route::get('/reports/{report}', [ ReportController::class, 'show' ]);
        Route::get('/reports/{report}/download', [ ReportController::class, 'download' ]);
        Route::get('reports/{report}/comments', [ CommentController::class, 'index' ]);
        Route::get('/comments/{comment}', [ CommentController::class, 'show' ]);
        Route::post('/comments', [ CommentController::class, 'store' ]);
        Route::patch('/comments/{comment}', [ CommentController::class, 'update' ]);
        Route::get('/categories', [ CategoryController::class, 'index' ]);
        Route::get('/categories/{category}', [ CategoryController::class, 'show' ]);
        Route::post('/profile', [ ProfileController::class, 'update']);
        Route::post('/reports/{report}/add-favorite', [ UserController::class, 'addFavorite' ]);
        Route::post('/reports/{report}/delete-favorite', [ UserController::class, 'deleteFavorite' ]);
    }
);

Route::middleware(['auth', 'role:Announcer'])->group(
    function () {
        Route::post('/reports', [ ReportController::class, 'store' ]);
    }
);

Route::middleware(['auth', 'role:Announcer', 'report_creator'])->group(
    function () {
        Route::patch('/reports/{report}', [ ReportController::class, 'update' ]);
        Route::delete('/reports/{report}', [ ReportController::class, 'destroy' ]);
    }
);

Route::middleware(['auth', 'role:Announcer|Listener'])->group(
    function () {
        Route::post('/conferences/{conference}/join', [ UserController::class, 'join' ]);
        Route::post('/conferences/{conference}/cancel', [ UserController::class, 'cancel' ]);
        }
);

Route::middleware(['auth', 'role:Admin'])->group(
    function () {
        Route::post('/categories', [ CategoryController::class, 'store' ]);
        Route::patch('/categories/{category}', [ CategoryController::class, 'update' ]);
        Route::delete('/categories/{category}', [ CategoryController::class, 'destroy' ]);
    }
);


Route::group(
    ['middleware' => ['permission:update conference', 'creator']],
    function () {
        Route::patch('/conferences/{conference}', [ ConferenceController::class, 'update' ]);
    }
);

Route::group(
    ['middleware' => ['permission:delete conference', 'creator']],
    function () {
        Route::delete('/conferences/{conference}', [ ConferenceController::class, 'destroy' ]);
    }
);


require __DIR__.'/auth.php';

