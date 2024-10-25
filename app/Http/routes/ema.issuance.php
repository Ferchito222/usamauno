<?php
/*
 * Route Issuance EMA
 */
Route::group([ 'prefix' => 'ema/{rp_id}' ], function () {
    /*
     * Header create
     */
    Route::get('create', [
        'as'   => 'ema.create',
        'uses' => 'Ema\HeaderController@create'
    ]);

    Route::post('create', [
        'as'   => 'ema.store',
        'uses' => 'Ema\HeaderController@store'
    ]);
    
    /*
     * Header Edit
     */
    Route::get('edit/{header_id}', [
        'as'   => 'ema.edit',
        'uses' => 'Ema\HeaderController@edit'
    ]);

    Route::put('edit/{header_id}', [
        'as'   => 'ema.update',
        'uses' => 'Ema\HeaderController@update'
    ]);

    Route::put('edit/{header_id}/{id_facultative}', [
        'as'   => 'ema.update.fa',
        'uses' => 'Ema\HeaderController@updateFa'
    ]);

    /*
     * Header Result
     */
    Route::get('create/{header_id}/result', [
        'as'   => 'ema.result',
        'uses' => 'Ema\HeaderController@result'
    ]);

    /*
     * Header issuance
     */
    Route::get('issuance/{header_id}/result', [
        'as'   => 'ema.show.issuance',
        'uses' => 'Ema\HeaderController@showIssuance'
    ]);

    Route::get('issue/{header_id}', [
        'as'   => 'ema.issue',
        'uses' => 'Ema\HeaderController@updateIssuance'
    ]);

    /*
     * Client Edit
     */
    Route::get('edit/{header_id}/client/edit/{client_id}', [
        'as'   => 'ema.client.i.edit',
        'uses' => 'Ema\HeaderController@editClient'
    ]);

    Route::put('edit/{header_id}/client/edit/{client_id}', [
        'as'   => 'ema.client.i.update',
        'uses' => 'Ema\HeaderController@updateClient'
    ]);

    /***
     * list detail
     */
    Route::get('create/{header_id}/detail/lists', [
        'as'   => 'ema.detail.lists',
        'uses' => 'Ema\DetailController@lists'
    ]);

    Route::get('create/{header_id}/vehicle/create', [
        'as'   => 'ema.vh.create',
        'uses' => 'Ema\DetailController@create'
    ]);

    Route::post('create/{header_id}/vehicle/create', [
        'as'   => 'ema.vh.store',
        'uses' => 'Ema\DetailController@store'
    ]);

    /*
     * detail delete
     */
    Route::delete('{header_id}/detail/delete/{detail_id}', [
        'as'   => 'ema.detail.destroy',
        'uses' => 'Ema\DetailController@destroy'
    ]);

    /*
     * Detail edit
     */
    Route::get('create/{header_id}/detail/edit/{detail_id}', [
        'as'   => 'ema.detail.edit',
        'uses' => 'Ema\DetailController@edit'
    ]);

    Route::put('create/{header_id}/vehicle/edit/{detail_id}', [
        'as'   => 'ema.detail.update',
        'uses' => 'Ema\DetailController@update'
    ]);

    /*
     * edit issuance
     */
    Route::get('edit/{header_id}/detail/edit/{detail_id?}', [
        'as'   => 'ema.detail.i.edit',
        'uses' => 'Ema\DetailController@editIssuance'
    ]);

    Route::put('edit/{header_id}/detail/edit/{detail_id?}', [
        'as'   => 'ema.detail.i.update',
        'uses' => 'Ema\DetailController@updateIssuance'
    ]);

    /*
     * Header Facultative
     */
    Route::get('request-approval/{header_id}', [
        'as'   => 'ema.fa.request.create',
        'uses' => 'Ema\HeaderController@requestCreate'
    ]);

    Route::put('request-approval/{header_id}', [
        'as'   => 'ema.fa.request.store',
        'uses' => 'Ema\HeaderController@requestStore'
    ]);

    /*
     * Cancellations
     */
    Route::get('cancel', [
        'as'   => 'ema.cancel.lists',
        'uses' => 'Ema\CancellationController@lists'
    ]);

    Route::get('cancel/{header_id}/create', [
        'as'   => 'ema.cancel.create',
        'uses' => 'Ema\CancellationController@create'
    ]);

    Route::post('cancel/{header_id}/create', [
        'as'   => 'ema.cancel.store',
        'uses' => 'Ema\CancellationController@store'
    ]);

    /*
     * Pre-Approved
     */
    Route::get('pre-approved', [
        'as'   => 'ema.pre.approved.lists',
        'uses' => 'Ema\PreApprovedController@lists'
    ]);

    /*
     * Issue Quote
     */
    Route::get('issue', [
        'as'   => 'ema.issue.lists',
        'uses' => 'Ema\IssueController@lists'
    ]);

    /*
     * Coverage
     */
    Route::get('coverage/{de_id}/create', [
        'as'   => 'ema.coverage.create',
        'uses' => 'Ema\HeaderController@coverageCreate'
    ]);

    Route::post('coverage/{de_id}/create', [
        'as'   => 'ema.coverage.store',
        'uses' => 'Ema\HeaderController@coverageStore'
    ]);

    Route::get('coverage/{de_id}/edit/{header_id}', [
        'as'   => 'ema.coverage.edit',
        'uses' => 'Ema\HeaderController@coverageEdit'
    ]);

    Route::put('coverage/{de_id}/edit/{header_id}', [
        'as'   => 'ema.coverage.update',
        'uses' => 'Ema\HeaderController@coverageUpdate'
    ]);
    
    /*
     * Header Upload Certificate
     */
    Route::post('{header_id}/upload/certificate', [
        'as'   => 'ema.upload.certificate',
        'uses' => 'Ema\HeaderController@uploadCertificate'
    ]);

});

Route::group([ 'prefix' => 'fema/{rp_id}' ], function () {
    /*
     * Facultative process
     */
    Route::get('facultative/edit/{id}', [
        'as'   => 'ema.fa.edit',
        'uses' => 'Ema\FacultativeController@edit'
    ]);

    Route::put('facultative/edit/{id}', [
        'as'   => 'ema.fa.update',
        'uses' => 'Ema\FacultativeController@update'
    ]);

    Route::get('facultative/{id}/observation', [
        'as'   => 'ema.fa.observation',
        'uses' => 'Ema\FacultativeController@observation'
    ]);

    Route::get('facultative/{id}/answer/{id_observation}', [
        'as'   => 'ema.fa.create.answer',
        'uses' => 'Ema\FacultativeController@createAnswer'
    ]);

    Route::put('facultative/{id}/answer/{id_observation}', [
        'as'   => 'ema.fa.store.answer',
        'uses' => 'Ema\FacultativeController@storeAnswer'
    ]);

    Route::get('facultative/{id}/response/{id_observation}', [
        'as'   => 'ema.fa.response',
        'uses' => 'Ema\FacultativeController@response'
    ]);

    Route::get('facultative/{id}/observation-process', [
        'as'   => 'ema.fa.observation.process',
        'uses' => 'Ema\FacultativeController@observationProcess'
    ]);

    Route::put('facultative/read/edit/{id}', [
        'as'   => 'ema.fa.read.update',
        'uses' => 'Ema\FacultativeController@readUpdate'
    ]);

});