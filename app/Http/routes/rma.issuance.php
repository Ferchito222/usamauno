<?php
/*
 * Route Issuance RMA
 */
Route::group([ 'prefix' => 'rma/{rp_id}' ], function () {
    /*
     * Header create
     */
    Route::get('create', [
        'as'   => 'rma.create',
        'uses' => 'Rma\HeaderController@create'
    ]);

    Route::post('create', [
        'as'   => 'rma.store',
        'uses' => 'Rma\HeaderController@store'
    ]);
    
    /*
     * Header Edit
     */
    Route::get('edit/{header_id}', [
        'as'   => 'rma.edit',
        'uses' => 'Rma\HeaderController@edit'
    ]);

    Route::put('edit/{header_id}', [
        'as'   => 'rma.update',
        'uses' => 'Rma\HeaderController@update'
    ]);

    Route::put('edit/{header_id}/{id_facultative}', [
        'as'   => 'rma.update.fa',
        'uses' => 'Rma\HeaderController@updateFa'
    ]);

    /*
     * Header Result
     */
    Route::get('create/{header_id}/result', [
        'as'   => 'rma.result',
        'uses' => 'Rma\HeaderController@result'
    ]);

    /*
     * Header issuance
     */
    Route::get('issuance/{header_id}/result', [
        'as'   => 'rma.show.issuance',
        'uses' => 'Rma\HeaderController@showIssuance'
    ]);

    Route::get('issue/{header_id}', [
        'as'   => 'rma.issue',
        'uses' => 'Rma\HeaderController@updateIssuance'
    ]);

    /*
     * Client Edit
     */
    Route::get('edit/{header_id}/client/edit/{client_id}', [
        'as'   => 'rma.client.i.edit',
        'uses' => 'Rma\HeaderController@editClient'
    ]);

    Route::put('edit/{header_id}/client/edit/{client_id}', [
        'as'   => 'rma.client.i.update',
        'uses' => 'Rma\HeaderController@updateClient'
    ]);

    /***
     * list detail
     */
    Route::get('create/{header_id}/detail/lists', [
        'as'   => 'rma.detail.lists',
        'uses' => 'Rma\DetailController@lists'
    ]);

    Route::get('create/{header_id}/vehicle/create', [
        'as'   => 'rma.vh.create',
        'uses' => 'Rma\DetailController@create'
    ]);

    Route::post('create/{header_id}/vehicle/create', [
        'as'   => 'rma.vh.store',
        'uses' => 'Rma\DetailController@store'
    ]);

    /*
     * detail delete
     */
    Route::delete('{header_id}/detail/delete/{detail_id}', [
        'as'   => 'rma.detail.destroy',
        'uses' => 'Rma\DetailController@destroy'
    ]);

    /*
     * Detail edit
     */
    Route::get('create/{header_id}/detail/edit/{detail_id}', [
        'as'   => 'rma.detail.edit',
        'uses' => 'Rma\DetailController@edit'
    ]);

    Route::put('create/{header_id}/vehicle/edit/{detail_id}', [
        'as'   => 'rma.detail.update',
        'uses' => 'Rma\DetailController@update'
    ]);

    /*
     * edit issuance
     */
    Route::get('edit/{header_id}/detail/edit/{detail_id?}', [
        'as'   => 'rma.detail.i.edit',
        'uses' => 'Rma\DetailController@editIssuance'
    ]);

    Route::put('edit/{header_id}/detail/edit/{detail_id?}', [
        'as'   => 'rma.detail.i.update',
        'uses' => 'Rma\DetailController@updateIssuance'
    ]);

    /*
     * Header Facultative
     */
    Route::get('request-approval/{header_id}', [
        'as'   => 'rma.fa.request.create',
        'uses' => 'Rma\HeaderController@requestCreate'
    ]);

    Route::put('request-approval/{header_id}', [
        'as'   => 'rma.fa.request.store',
        'uses' => 'Rma\HeaderController@requestStore'
    ]);

    /*
     * Cancellations
     */
    Route::get('cancel', [
        'as'   => 'rma.cancel.lists',
        'uses' => 'Rma\CancellationController@lists'
    ]);

    Route::get('cancel/{header_id}/create', [
        'as'   => 'rma.cancel.create',
        'uses' => 'Rma\CancellationController@create'
    ]);

    Route::post('cancel/{header_id}/create', [
        'as'   => 'rma.cancel.store',
        'uses' => 'Rma\CancellationController@store'
    ]);

    /*
     * Pre-Approved
     */
    Route::get('pre-approved', [
        'as'   => 'rma.pre.approved.lists',
        'uses' => 'Rma\PreApprovedController@lists'
    ]);

    /*
     * Issue Quote
     */
    Route::get('issue', [
        'as'   => 'rma.issue.lists',
        'uses' => 'Rma\IssueController@lists'
    ]);

    /*
     * Coverage
     */
    Route::get('coverage/{de_id}/create', [
        'as'   => 'rma.coverage.create',
        'uses' => 'Rma\HeaderController@coverageCreate'
    ]);

    Route::post('coverage/{de_id}/create', [
        'as'   => 'rma.coverage.store',
        'uses' => 'Rma\HeaderController@coverageStore'
    ]);

    Route::get('coverage/{de_id}/edit/{header_id}', [
        'as'   => 'rma.coverage.edit',
        'uses' => 'Rma\HeaderController@coverageEdit'
    ]);

    Route::put('coverage/{de_id}/edit/{header_id}', [
        'as'   => 'rma.coverage.update',
        'uses' => 'Rma\HeaderController@coverageUpdate'
    ]);
    
    /*
     * Header Upload Certificate
     */
    Route::post('{header_id}/upload/certificate', [
        'as'   => 'rma.upload.certificate',
        'uses' => 'Rma\HeaderController@uploadCertificate'
    ]);

});

Route::group([ 'prefix' => 'frma/{rp_id}' ], function () {
    /*
     * Facultative process
     */
    Route::get('facultative/edit/{id}', [
        'as'   => 'rma.fa.edit',
        'uses' => 'Rma\FacultativeController@edit'
    ]);

    Route::put('facultative/edit/{id}', [
        'as'   => 'rma.fa.update',
        'uses' => 'Rma\FacultativeController@update'
    ]);

    Route::get('facultative/{id}/observation', [
        'as'   => 'rma.fa.observation',
        'uses' => 'Rma\FacultativeController@observation'
    ]);

    Route::get('facultative/{id}/answer/{id_observation}', [
        'as'   => 'rma.fa.create.answer',
        'uses' => 'Rma\FacultativeController@createAnswer'
    ]);

    Route::put('facultative/{id}/answer/{id_observation}', [
        'as'   => 'rma.fa.store.answer',
        'uses' => 'Rma\FacultativeController@storeAnswer'
    ]);

    Route::get('facultative/{id}/response/{id_observation}', [
        'as'   => 'rma.fa.response',
        'uses' => 'Rma\FacultativeController@response'
    ]);

    Route::get('facultative/{id}/observation-process', [
        'as'   => 'rma.fa.observation.process',
        'uses' => 'Rma\FacultativeController@observationProcess'
    ]);

    Route::put('facultative/read/edit/{id}', [
        'as'   => 'rma.fa.read.update',
        'uses' => 'Rma\FacultativeController@readUpdate'
    ]);

});