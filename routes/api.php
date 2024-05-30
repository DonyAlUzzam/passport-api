<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\Book\CategoryController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group([
//     'as' => 'passport.',
//     'prefix' => config('passport.path', 'oauth'),
//     'namespace' => '\Laravel\Passport\Http\Controllers',
// ], function () {
//     Route::get('/posts', [PostController::class, 'index']);
//     Route::post('/posts', [PostController::class, 'store']);
// });


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::controller(BookController::class)->group(function(){
        Route::group(['prefix' => 'books'], function() {
            Route::get('/list', 'index');
            Route::get('/find', 'show');
            Route::post('/create', 'store');
            Route::put('/update', 'update');
            Route::delete('/delete', 'destroy');
        });
    });

    Route::controller(CategoryController::class)->group(function(){
        Route::group(['prefix' => 'categories'], function() {
            Route::get('/list', 'index');
            Route::get('/find', 'show');
            Route::post('/create', 'store');
            Route::put('/update', 'update');
            Route::delete('/delete', 'destroy');
        });
    });

});