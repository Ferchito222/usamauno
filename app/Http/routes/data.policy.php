<?php

Route::group([ 'prefix' => 'data-policy' ], function () {
    Route::get('search', [
        'as'   => 'dp.search',
        'uses' => 'DataPolicyController@search'
    ]);

    Route::get('{code}/{rp}/edit-policy/{id}', [
        'as'   => 'dp.policy.edit',
        'uses' => 'DataPolicyController@editPolicy'
    ]);

    Route::put('{code}/{rp}/edit-policy/{id}', [
        'as'   => 'dp.policy.update',
        'uses' => 'DataPolicyController@updatePolicy'
    ]);

    Route::get('{code}/{rp}/edit-result/{id}/{logId}', [
        'as'   => 'dp.policy.result',
        'uses' => 'DataPolicyController@result'
    ]);
});