<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\drugGeneralController;
use App\Http\Controllers\Api\v1\drugImportController;
use App\Http\Controllers\Api\v1\testApi;

// use App\Http\Controllers\Api\v1\drugGeneralController;


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
Route::resource('drug-general',drugGeneralController::class);
Route::resource('drug-import',drugImportController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
