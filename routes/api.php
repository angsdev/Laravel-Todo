<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\UserTaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function(){

  Route::post('register', [ UserController::class, 'store' ]);
  Route::post('login', [ LoginController::class, 'login' ]);
});

Route::middleware('auth:api')->group(function(){

  /** User Profile **/
  Route::get('profile', fn(Request $request) => $request->user()->load('tasks'));
  /** Resources endpoints **/
  Route::apiResources([
    'users' => UserController::class,
    'users.tasks' => UserTaskController::class,
    'tasks' => TaskController::class
  ]);
  Route::match([ 'post', 'get' ], 'logout', [ LoginController::class, 'logout' ]);
});

Route::fallback(fn() => response()->json(['success' => false, 'message' => 'Resource not found.'], 404));
