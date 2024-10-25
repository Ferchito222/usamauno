<?php
//edw
Route::get('td/{rp_id}/td_list_insured/{header_id}/{steep}/', [
    'as'   => 'td.list.insured',
    'uses' => 'Td\HeaderController@listInsured'
]);

/*
 * Route Issuance TD
 */
Route::group([ 'prefix' => 'td/{rp_id}' ], function () {
    /*
     * Header create
     */
    //edw
    Route::get('create', [
        'as'   => 'td.create',
        'uses' => 'Td\HeaderController@create'
    ]);
    //edw
    Route::post('create', [
        'as'   => 'td.store',
        'uses' => 'Td\HeaderController@store'
    ]);
    //edw
    Route::get('create/{header_id}/multiriesgo/insured', [
        'as'   => 'td.mr.insured',
        'uses' => 'Td\HeaderController@insured'
    ]);
    //
    Route::get('td_form_insured/{header_id}/{id_detail}/{steep}/', [
        'as'   => 'td.form.insured',
        'uses' => 'Td\HeaderController@formDetail'
    ]);
    //edw
    Route::post('create_insured/{header_id}', [
        'as'   => 'td.save.insured',
        'uses' => 'Td\HeaderController@storeInsured'
    ]);

    /*
     * Header Edit
     */
    //edw
    Route::get('edit/{header_id}', [
        'as'   => 'td.edit',
        'uses' => 'Td\HeaderController@edit'
    ]);
    //edw
    Route::put('edit/{header_id}', [
        'as'   => 'td.update',
        'uses' => 'Td\HeaderController@update'
    ]);

    /*
     * Header Result
     */
    Route::get('create/{header_id}/result', [
        'as'   => 'td.result',
        'uses' => 'Td\HeaderController@result'
    ]);

    /*
     * Header issuance
     */
    //edw
    Route::get('issuance/{header_id}/result', [
        'as'   => 'td.show.issuance',
        'uses' => 'Td\HeaderController@showIssuance'
    ]);
    //edw
    Route::get('issue/{header_id}', [
        'as'   => 'td.issue',
        'uses' => 'Td\HeaderController@updateIssuance'
    ]);

    /*
     * Client Edit
     */
    //edw
    Route::get('edit/{header_id}/client/edit/{client_id}', [
        'as'   => 'td.client.i.edit',
        'uses' => 'Td\HeaderController@editClient'
    ]);

    Route::put('edit/{header_id}/client/edit/{client_id}', [
        'as'   => 'td.client.i.update',
        'uses' => 'Td\HeaderController@updateClient'
    ]);

    /*
     * Cancellations
     */
    Route::get('cancel', [
        'as'   => 'td.cancel.lists',
        'uses' => 'Td\CancellationController@lists'
    ]);

    Route::get('cancel/{header_id}/create', [
        'as'   => 'td.cancel.create',
        'uses' => 'Td\CancellationController@create'
    ]);

    Route::post('cancel/{header_id}/create', [
        'as'   => 'td.cancel.store',
        'uses' => 'Td\CancellationController@store'
    ]);

    /*
     * Pre-Approved
     */
    Route::get('pre-approved', [
        'as'   => 'td.pre.approved.lists',
        'uses' => 'Td\PreApprovedController@lists'
    ]);

    /*
     * Issue Quote
     */
    Route::get('issue', [
        'as'   => 'td.issue.lists',
        'uses' => 'Td\IssueController@lists'
    ]);

    /*
     * Header Facultative
     */
    Route::get('request-approval/{header_id}', [
        'as'   => 'td.fa.request.create',
        'uses' => 'Td\HeaderController@requestCreate'
    ]);

    Route::put('rrequest-approval/{header_id}', [
        'as'   => 'td.fa.request.store',
        'uses' => 'Td\HeaderController@requestStore'
    ]);

    /*
     * Coverage
     */
    Route::get('coverage/{de_id}/create', [
        'as'   => 'td.coverage.create',
        'uses' => 'Td\HeaderController@coverageCreate'
    ]);

    Route::post('coverage/{de_id}/create', [
        'as'   => 'td.coverage.store',
        'uses' => 'Td\HeaderController@coverageStore'
    ]);

    Route::get('coverage/{de_id}/edit/{header_id}', [
        'as'   => 'td.coverage.edit',
        'uses' => 'Td\HeaderController@coverageEdit'
    ]);

    Route::put('coverage/{de_id}/edit/{header_id}', [
        'as'   => 'td.coverage.update',
        'uses' => 'Td\HeaderController@coverageUpdate'
    ]);

    /*
     * Property edit issuance
     */
    Route::get('edit/{header_id}/property/edit/{detail_id?}', [
        'as'   => 'td.pr.i.edit',
        'uses' => 'Td\DetailController@editIssuance'
    ]);

    Route::put('edit/{header_id}/property/edit/{detail_id?}', [
        'as'   => 'td.pr.i.update',
        'uses' => 'Td\DetailController@updateIssuance'
    ]);

    /*
     * Risk create
     */
    Route::get('create/{header_id}/riesgo/lists', [
        'as'   => 'td.mr.lists',
        'uses' => 'Td\DetailController@lists'
    ]);

    Route::get('create/{header_id}/riesgo/create', [
        'as'   => 'td.mr.create',
        'uses' => 'Td\DetailController@create'
    ]);

    Route::post('create/{header_id}/riesgo/create', [
        'as'   => 'td.mr.store',
        'uses' => 'Td\DetailController@store'
    ]);
    /*
     * Risk destroy
     */
    Route::delete('create/{header_id}/risk/delete/{detail_id}', [
        'as'   => 'td.mr.destroy',
        'uses' => 'Td\DetailController@destroy'
    ]);

    /*
     * Risk edit
     */
    Route::get('create/{header_id}/riesgo/edit/{detail_id}', [
        'as'   => 'td.mr.edit',
        'uses' => 'Td\DetailController@edit'
    ]);

    Route::put('create/{header_id}/riesgo/edit/{detail_id}', [
        'as'   => 'td.mr.update',
        'uses' => 'Td\DetailController@update'
    ]);
    
    /*
     * Risk edit issuance
     */
    Route::get('edit/{header_id}/risk/edit/{detail_id?}', [
        'as'   => 'td.mr.i.edit',
        'uses' => 'Td\DetailController@editIssuance'
    ]);

    Route::put('edit/{header_id}/risk/edit/{detail_id?}', [
        'as'   => 'td.mr.i.update',
        'uses' => 'Td\DetailController@updateIssuance'
    ]);
    
    /*
     * Header Upload Certificate
     */
    Route::post('{header_id}/upload/certificate', [
        'as'   => 'td.upload.certificate',
        'uses' => 'Td\HeaderController@uploadCertificate'
    ]);
});

Route::group([ 'prefix' => 'ftd/{rp_id}' ], function () {
    /*
     * Facultative process
     */
    Route::get('facultative/edit/{id}', [
        'as'   => 'td.fa.edit',
        'uses' => 'Td\FacultativeController@edit'
    ]);

    Route::put('facultative/edit/{id}', [
        'as'   => 'td.fa.update',
        'uses' => 'Td\FacultativeController@update'
    ]);

    Route::get('facultative/{id}/observation', [
        'as'   => 'td.fa.observation',
        'uses' => 'Td\FacultativeController@observation'
    ]);

    Route::get('facultative/{id}/answer/{id_observation}', [
        'as'   => 'td.fa.create.answer',
        'uses' => 'Td\FacultativeController@createAnswer'
    ]);

    Route::put('facultative/{id}/answer/{id_observation}', [
        'as'   => 'td.fa.store.answer',
        'uses' => 'Td\FacultativeController@storeAnswer'
    ]);

    Route::get('facultative/{id}/response/{id_observation}', [
        'as'   => 'td.fa.response',
        'uses' => 'Td\FacultativeController@response'
    ]);

    Route::get('facultative/{id}/observation-process', [
        'as'   => 'td.fa.observation.process',
        'uses' => 'Td\FacultativeController@observationProcess'
    ]);

    Route::put('facultative/read/edit/{id}', [
        'as'   => 'td.fa.read.update',
        'uses' => 'Td\FacultativeController@readUpdate'
    ]);

});


