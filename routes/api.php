<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsTypeController;
use App\Http\Controllers\FroalaController;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:api'], function() {
      Route::get('logout', [AuthController::class, 'logout']);
      Route::get('user', [AuthController::class, 'user']);
    });
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/{id}', [UserController::class, 'get']);
    Route::get('/', [UserController::class, 'getAll']);
    Route::post('/', [UserController::class, 'add']);
    Route::put('/{id}', [UserController::class, 'edit']);
    Route::delete('/{id}', [UserController::class, 'delete']);
});


Route::group(['prefix' => 'banner'], function () {
  Route::put('/level/{id}', [BannerController::class, 'editLevel']);
  Route::get('/{id}', [BannerController::class, 'get']);
  Route::get('/', [BannerController::class, 'getAll']);
  Route::post('/', [BannerController::class, 'add']);
  Route::put('/{id}', [BannerController::class, 'edit']);
  Route::delete('/{id}', [BannerController::class, 'delete']);
});

Route::group(['prefix' => 'news'], function () {
  Route::get('/{id}', [NewsController::class, 'get']);
  Route::get('/', [NewsController::class, 'getAll']);
  Route::post('/', [NewsController::class, 'add']);
  Route::put('/{id}', [NewsController::class, 'edit']);
  Route::delete('/{id}', [NewsController::class, 'delete']);
});

Route::group(['prefix' => 'news-type'], function () {
  Route::get('/{id}', [NewsTypeController::class, 'get']);
  Route::get('/', [NewsTypeController::class, 'getAll']);
  Route::post('/', [NewsTypeController::class, 'add']);
  Route::put('/{id}', [NewsTypeController::class, 'edit']);
  Route::delete('/{id}', [NewsTypeController::class, 'delete']);
});

Route::group(['prefix' => 'froala'], function () {
  Route::post('/image', [FroalaController::class, 'image']);
  Route::post('/document', [FroalaController::class, 'document']);
  Route::post('/video', [FroalaController::class, 'video']);
});