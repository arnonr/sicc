<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsTypeController;
use App\Http\Controllers\FroalaController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TeamController;

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

Route::group(['prefix' => 'about-us'], function () {
  Route::put('/level/{id}', [AboutUsController::class, 'editLevel']);
  Route::get('/{id}', [AboutUsController::class, 'get']);
  Route::get('/', [AboutUsController::class, 'getAll']);
  Route::post('/', [AboutUsController::class, 'add']);
  Route::put('/{id}', [AboutUsController::class, 'edit']);
  Route::delete('/{id}', [AboutUsController::class, 'delete']);
});

Route::group(['prefix' => 'contact-us'], function () {
  Route::get('/{id}', [ContactUsController::class, 'get']);
  Route::get('/', [ContactUsController::class, 'getAll']);
  Route::post('/', [ContactUsController::class, 'add']);
  Route::put('/{id}', [ContactUsController::class, 'edit']);
  Route::delete('/{id}', [ContactUsController::class, 'delete']);
});

Route::group(['prefix' => 'department'], function () {
  Route::put('/level/{id}', [DepartmentController::class, 'editLevel']);
  Route::get('/{id}', [DepartmentController::class, 'get']);
  Route::get('/', [DepartmentController::class, 'getAll']);
  Route::post('/', [DepartmentController::class, 'add']);
  Route::put('/{id}', [DepartmentController::class, 'edit']);
  Route::delete('/{id}', [DepartmentController::class, 'delete']);
});

Route::group(['prefix' => 'team'], function () {
  Route::put('/level/{id}', [TeamController::class, 'editLevel']);
  Route::get('/{id}', [TeamController::class, 'get']);
  Route::get('/', [TeamController::class, 'getAll']);
  Route::post('/', [TeamController::class, 'add']);
  Route::put('/{id}', [TeamController::class, 'edit']);
  Route::delete('/{id}', [TeamController::class, 'delete']);
});

Route::group(['prefix' => 'froala'], function () {
  Route::post('/image', [FroalaController::class, 'image']);
  Route::post('/document', [FroalaController::class, 'document']);
  Route::post('/video', [FroalaController::class, 'video']);
});