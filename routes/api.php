<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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
Route::post('register' , [AuthController::class, 'register']);
Route::post('login' , [AuthController::class, 'login']);

Route::middleware('jwt.auth')->group(function(){
	Route::get('me' , [AuthController::class, 'me']);
	Route::get('refresh' , [AuthController::class, 'refresh']);
	Route::get('logout' , [AuthController::class, 'logout']);

	Route::get('users', [UserController::class, 'index']);
	Route::get('users/{id}', [UserController::class, 'show']);

});

