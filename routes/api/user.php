<?php

use App\Http\Controllers\Auth\UserAuthApiController;
use App\Http\Controllers\User\UserBlogApiController;
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
Route::post('user/register', [UserAuthApiController::class, 'register']);
Route::post('user/login', [UserAuthApiController::class, 'login']);
Route::group(['prefix' => 'user', 'middleware' => ['auth:user-api', 'scopes:user']], function ()
{
  // authenticated staff routes here
  Route::get('dashboard', [UserAuthApiController::class, 'dashboard']);
  Route::get('blogs', [UserBlogApiController::class, 'index']);
  Route::post('blogs/add', [UserBlogApiController::class, 'store']);
  Route::get('blogs/delete/{id}', [UserBlogApiController::class, 'destroy']);
  Route::post('logout', [UserAuthApiController::class, 'logout']);
});
