<?php

Route::group([ 'prefix' => 'auth' ], function () {
    Route::get('login', [
        'as'   => 'auth.login.get',
        'uses' => 'Auth\AuthController@getLogin',
    ]);

    Route::post('login', [
        'as'   => 'auth.login.post',
        'uses' => 'Auth\AuthController@postLogin',
    ]);

    Route::get('logout', [
        'as'   => 'auth.logout',
        'uses' => 'Auth\AuthController@getLogout',
    ]);

    Route::get('cashier/logout', [
        'as'   => 'auth.cashier.logout',
        'uses' => 'Auth\AuthController@getCashierLogout',
    ]);

    Route::post('api/token', [
        'as'   => 'auth.api.token',
        'uses' => 'Auth\AuthController@authenticate',
    ]);
});

/*
 * Password reset link request routes
 */
Route::get('password/email', [
    'as'   => 'password.create',
    'uses' => 'Auth\PasswordController@getEmail',
]);

Route::post('password/email', [
    'as'   => 'password.store',
    'uses' => 'Auth\PasswordController@postEmail',
]);

/*
 * Password reset routes
 */
Route::get('password/reset/{token}', [
    'as'   => 'reset.create',
    'uses' => 'Auth\PasswordController@getReset',
]);

Route::post('password/reset', [
    'as'   => 'reset.store',
    'uses' => 'Auth\PasswordController@postReset',
]);

/*
 * Cashiers
 */
Route::group([ 'middleware' => [ 'cashier' ], 'prefix' => 'cashiers', 'as' => 'cashiers::' ], function () {

    Route::get('panel', [
        'as'   => 'panel',
        'uses' => 'Retailer\CashierController@index',
    ]);

    /*
     * EB - Orden de Cobro
     */
    Route::get('panel/{code}/{rp_id}/order', [
        'as'   => 'order.show',
        'uses' => 'Retailer\CashierController@showOrder',
    ]);

    Route::post('panel/{code}/{rp_id}/order', [
        'as'   => 'order.search',
        'uses' => 'Retailer\CashierController@searchOrder',
    ]);

    Route::get('panel/{code}/{rp_id}/order/{header_id}/edit', [
        'as'   => 'order.edit',
        'uses' => 'Retailer\CashierController@orderEdit',
    ]);

    Route::put('panel/{code}/{rp_id}/order/{header_id}/edit', [
        'as'   => 'order.update',
        'uses' => 'Retailer\CashierController@orderUpdate',
    ]);

    Route::get('panel/{code}/{rp_id}/report/{format?}', [
        'as'   => 'report',
        'uses' => 'Retailer\CashierController@report',
    ]);
});

Route::group([ 'middleware' => [ 'auth.jwt' ], 'prefix' => 'fortaleza/api' ], function () {

    Route::post('post', [
        'as'   => 'laravel.post',
        'uses' => 'API\GeneralController@post',
    ]);

    Route::get('get', [
        'as'   => 'laravel.get',
        'uses' => 'API\GeneralController@get',
    ]);

});

Route::post('get/api/token', [
    'as'   => 'get.api.token',
    'uses' => 'Auth\AuthApiController@authenticate',
]);