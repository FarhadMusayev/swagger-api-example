<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\UploadController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('v1/auth/user', [AuthController::class, 'authToken'])->middleware('auth:sanctum');
Route::post('v1/login', [AuthController::class, 'login']);
Route::post('v1/register', [AuthController::class, 'register']);
Route::delete('v1/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('projects', ProjectsController::class);
Route::apiResource('users', UserController::class);
Route::post('upload/file', [UploadController::class, 'index']);
Route::post('upload/multiple-file', [UploadController::class, 'multiple']);
