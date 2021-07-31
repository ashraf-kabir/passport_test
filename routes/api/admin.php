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
Route::post('admin/register', [MainController::class, 'adminRegister'])->name('adminRegister');
Route::post('admin/login', [MainController::class, 'adminLogin'])->name('adminLogin');
Route::post('admin/logout', [MainController::class, 'adminLogout'])->name('adminLogout');
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin-api', 'scopes:admin']], function ()
{
  // authenticated staff routes here
  Route::get('dashboard', [MainController::class, 'adminDashboard']);
});
