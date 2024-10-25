<?php

Route::group([ 'prefix' => 'migration', 'as' => 'migration::' ], function () {
    Route::get('', [
        'as'   => 'index',
        'uses' => 'MigrationController@index',
    ]);

    Route::get('export', function () {
        return redirect()->route('migration::index');
    });

    Route::post('export', [
        'as'   => 'export',
        'uses' => 'MigrationController@export',
    ]);
    
    Route::get('users', [
        'as'   => 'users.form',
        'uses' => 'MigrationController@importUsers',
    ]);
    
    Route::post('users/store', [
        'as'   => 'users.read.xls',
        'uses' => 'MigrationController@readXls',
    ]);
});