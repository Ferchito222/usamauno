<?php

/*
 * Route Issuance TJ
 */
Route::group([ 'prefix' => $GLOBALS['prod'].'/{rp_id}' ], function () {
    
    Route::get('create', [
        'as'   => $GLOBALS['prod'].'.create',
        'uses' => ucwords($GLOBALS['prod']).'\DetailController@create'
    ]);

    Route::post('store', [
        'as'   => $GLOBALS['prod'].'.store',
        'uses' => ucwords($GLOBALS['prod']).'\DetailController@store'
    ]);
    
    Route::get('{header_id}/list', [
        'as'   => $GLOBALS['prod'].'.client.list',
        'uses' => ucwords($GLOBALS['prod']).'\HeaderController@lists'
    ]);
    
    /*
     * Client edit
     */
    Route::get('{header_id}/client/edit/{detail_id}', [
        'as'   => $GLOBALS['prod'].'.detail.edit',
        'uses' => ucwords($GLOBALS['prod']).'\DetailController@edit'
    ]);

    Route::put('{header_id}/client/edit/{detail_id}', [
        'as'   => $GLOBALS['prod'].'.detail.update',
        'uses' => ucwords($GLOBALS['prod']).'\DetailController@update'
    ]);
    
    /*
     * Header result on Quote
     */
    Route::get('{header_id}/result', [
        'as'   => $GLOBALS['prod'].'.result',
        'uses' => ucwords($GLOBALS['prod']).'\HeaderController@result'
    ]);
    
    /*
     * Header edit
     */
    Route::get('{header_id}/edit', [
        'as'   => $GLOBALS['prod'].'.edit',
        'uses' => ucwords($GLOBALS['prod']).'\HeaderController@edit'
    ]);
    
    Route::put('edit/{header_id}', [
        'as'   => $GLOBALS['prod'].'.update',
        'uses' => ucwords($GLOBALS['prod']).'\HeaderController@update'
    ]);
    
    /*
     * Header issuance
     */
    Route::get('issue/{header_id}', [
        'as'   => $GLOBALS['prod'].'.issue',
        'uses' => ucwords($GLOBALS['prod']).'\HeaderController@issuance'
    ]);
    
    Route::get('issuance/{header_id}/result', [
        'as'   => $GLOBALS['prod'].'.show.issuance',
        'uses' => ucwords($GLOBALS['prod']).'\HeaderController@showIssuance'
    ]);
    
    /*
     * Pre-Approved
     */
    Route::get('pre-approved', [
        'as'   => $GLOBALS['prod'].'.pre.approved.lists',
        'uses' => ucwords($GLOBALS['prod']).'\PreApprovedController@lists'
    ]);
    
    /*
     * Issue Quote
     */
    Route::get('issue/{guest?}', [
        'as'   => $GLOBALS['prod'].'.issue.lists',
        'uses' => ucwords($GLOBALS['prod']).'\IssueController@lists'
    ]);
    
    
    /*
     * Cancellations
     */
    Route::get('cancel', [
        'as'   => $GLOBALS['prod'].'.cancel.lists',
        'uses' => ucwords($GLOBALS['prod']).'\CancellationController@lists'
    ]);
    
   Route::get('cancel/{header_id}/create', [
        'as'   => $GLOBALS['prod'].'.cancel.create',
        'uses' => ucwords($GLOBALS['prod']).'\CancellationController@create'
    ]);

    Route::post('cancel/{header_id}/create', [
        'as'   => $GLOBALS['prod'].'.cancel.store',
        'uses' => ucwords($GLOBALS['prod']).'\CancellationController@store'
    ]);
    
    
    /*
     * Header Upload Certificate
     */
    Route::post('{header_id}/upload/certificate', [
        'as'   => 'tj.upload.certificate',
        'uses' => 'Tj\HeaderController@uploadCertificate'
    ]);
    
});


Route::group([ 'prefix' => $GLOBALS['prod'].'/{rp_id}/{header_id}' ], function () {
    /*
     * Route Beneficiary VI
     */
    Route::get('beneficiary/create/{detail_id}', [
        'as'   => $GLOBALS['prod'].'.beneficiary.create',
        'uses' => ucwords($GLOBALS['prod']).'\BeneficiaryController@create'
    ]);

    Route::post('beneficiary/create/{detail_id}', [
        'as'   => $GLOBALS['prod'].'.beneficiary.store',
        'uses' => ucwords($GLOBALS['prod']).'\BeneficiaryController@store'
    ]);

    Route::get('beneficiary/edit/{detail_id}/{beneficiary_id}', [
        'as'   => $GLOBALS['prod'].'.beneficiary.edit',
        'uses' => ucwords($GLOBALS['prod']).'\BeneficiaryController@edit'
    ]);

    Route::put('beneficiary/edit/{detail_id}/{beneficiary_id}', [
        'as'   => $GLOBALS['prod'].'.beneficiary.update',
        'uses' => ucwords($GLOBALS['prod']).'\BeneficiaryController@update'
    ]);
    
    Route::delete('beneficiary/{beneficiary_id}', [
        'as'   => $GLOBALS['prod'].'.beneficiary.destroy',
        'uses' => ucwords($GLOBALS['prod']).'\BeneficiaryController@destroy'
    ]);
    
    /*
     * Client edit complementary data
     */
    Route::get('edit/detail/edit/{detail_id}', [
        'as'   => $GLOBALS['prod'].'.detail.i.edit',
        'uses' => ucwords($GLOBALS['prod']).'\DetailController@editIssue'
    ]);

    Route::put('edit/detail/edit/{detail_id}', [
        'as'   => $GLOBALS['prod'].'.detail.i.update',
        'uses' => ucwords($GLOBALS['prod']).'\DetailController@updateIssue'
    ]);

});

