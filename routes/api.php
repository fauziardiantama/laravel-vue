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

Route::prefix('/items')->group(function () {
    Route::get('/', 'ItemController@index');
    Route::post('/', 'ItemController@store');
    Route::get('/{item}', 'ItemController@show');
    Route::put('/{item}', 'ItemController@update');
    Route::delete('/{item}', 'ItemController@destroy');
});
