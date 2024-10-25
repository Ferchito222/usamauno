<?php

Route::get('user/options', [
    'as'   => 'user.options',
    'uses' => 'UserController@options',
]);

Route::put('user/options/edit/{option}', [
    'as'   => 'user.options.update',
    'uses' => 'UserController@optionsUpdate',
]);