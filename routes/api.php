<?php

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

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\StudentController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ENDPOINT FOR LOGIN
Route::post('/login', [AuthController::class,'login']);

// PROTECTED ENDPOINTS
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/students', [StudentController::class,'index']);
    Route::post('/students', [StudentController::class,'upload']);
    Route::post('/search/students', [StudentController::class,'search']);
});
