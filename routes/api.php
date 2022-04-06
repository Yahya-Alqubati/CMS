<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\CashInController;
use App\Http\Controllers\CashOutController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//user api
Route::get("/users", [UserController::class,'get'])->middleware('auth:api');
Route::post("/users", [UserController::class, 'post']);
Route::patch("/users", [UserController::class, 'patch'])->middleware('auth:api');

//this api for payment game
Route::patch("/payment", [CardController::class,'patch']);

//this api for add cash in to card
Route::post("/cashIn", [CashInController::class, 'store']);

//note card and game add it from dashboard

