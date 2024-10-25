<?php

/*
 * Route Question
 */
Route::group(['prefix' => 'de/{rp_id}/{header_id}/client/{detail_id}'], function() {
    Route::get('question/create', [
        'as'    => 'de.question.create',
        'uses'  => 'Client\QuestionController@create'
    ]);

    Route::post('question/create', [
        'as'    => 'de.question.store',
        'uses'  => 'Client\QuestionController@storeDe'
    ]);

    Route::get('question/edit', [
        'as'    => 'de.question.edit',
        'uses'  => 'Client\QuestionController@edit'
    ]);

    Route::put('question/edit', [
        'as'    => 'de.question.update',
        'uses'  => 'Client\QuestionController@updateDe'
    ]);
});