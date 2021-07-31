<?php

use App\Http\Controllers\AdminAuthApiController;
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
Route::post('admin/register', [AdminAuthApiController::class, 'register']);
Route::post('admin/login', [AdminAuthApiController::class, 'login']);
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin-api', 'scopes:admin']], function ()
{
  // authenticated staff routes here
  Route::get('dashboard', [AdminAuthApiController::class, 'dashboard']);
  Route::post('logout', [AdminAuthApiController::class, 'logout']);
});
