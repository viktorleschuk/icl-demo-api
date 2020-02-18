<?php

use Illuminate\Http\Request;

Route::post('auth/login', 'Auth\LoginController@login');

Route::group(['middleware' => 'auth:api'], function () {

    // Auth
    Route::get('auth/user', 'UserController');
    Route::post('auth/logout', 'Auth\LoginController@logout');

    // Order resource
    Route::resource('orders', 'OrderController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);

    // Order Items (Products) resource
    Route::post('/orders/{order}/items/import', 'OrderItemController@import');
    Route::resource('orders.items', 'OrderItemController', ['only' => ['index']]);
});
