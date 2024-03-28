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

Route::get('/test', function (Request $request) {
    return response()->json([
        'severity' => 'success',
        'message' => "Fecha y hora actual: " . date('Y-m-d H:i:s'),
        'code' => 200]
    );
});


Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', ['uses' => 'Seguridad\AuthController@login']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/access', function (Request $request) {
        return 'Acceeso con login';
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/logout', ['uses' => 'Seguridad\AuthController@logout']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', ['uses' => 'UserController@index']);
        Route::get('/{id}', ['uses' => 'UserController@show']);
        Route::post('/', ['uses' => 'UserController@store']);
        Route::put('/{id}', ['uses' => 'UserController@update']);
        Route::delete('/{id}', ['uses' => 'UserController@destroy']);
    });
});
