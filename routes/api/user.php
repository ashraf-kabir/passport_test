<?php

use App\Http\Controllers\MainController;
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
Route::post('user/register', [MainController::class, 'userRegister'])->name('userRegister');
Route::post('user/login', [MainController::class, 'userLogin'])->name('userLogin');
Route::group(['prefix' => 'user', 'middleware' => ['auth:user-api', 'scopes:user']], function ()
{
  // authenticated staff routes here
  Route::get('dashboard', [MainController::class, 'userDashboard']);
  Route::post('logout', [MainController::class, 'userLogout']);
});
