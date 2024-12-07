<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\TrashCanController;

Route::get('/trashcan', [TrashCanController::class, 'serach_nearby_trash_can']);

Route::post('/trashcan', [TrashCanController::class, 'create']);

Route::get('/trashcan/{id}', [TrashCanController::class, 'show']);

Route::delete('/trashcan/{id}', [TrashCanController::class, 'delete']);
