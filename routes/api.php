<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;

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
//API route for register new user
Route::post('/register', [AuthController::class, 'register']);//passed
//API route for login user
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

Route::group(['middleware' => ['auth:sanctum']], function () {
    //API route for get Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/attendance', [AttendanceController::class, 'store']);
    Route::get('/attendance/history', [AttendanceController::class, 'history']);

});
