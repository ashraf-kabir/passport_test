<?php

use App\Http\Controllers\UserAuthApiController;
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
  Route::post('logout', [UserAuthApiController::class, 'logout']);
});
