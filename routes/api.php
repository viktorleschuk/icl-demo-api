<?php

use Illuminate\Http\Request;

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
Route::post('auth/login', 'Auth\LoginController@login');

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('auth/logout', 'Auth\LoginController@logout');

    Route::resource('orders', 'OrderController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('orders.items', 'OrderItemController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
});
