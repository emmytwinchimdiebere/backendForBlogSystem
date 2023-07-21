<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLogOutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function(){
    Route::post("/v1/user/logout", [UserLogOutController::class, "revokeToken"]);
    Route::post("/v1/user/create/post", [PostController::class, "createPost"]);
});

Route::post("/v1/login", [LoginController::class, "LoginUser"]);
Route::post("/v1/user/signup", [UserController::class, "UserSignUp"]);
