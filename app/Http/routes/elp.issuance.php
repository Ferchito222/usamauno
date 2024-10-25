<?php

/*
 * Route Issuance ELP
 */
Route::group(['prefix' => 'elp/{rp_id}'], function () {

    /*
     * Client create
     */
    Route::get('{header_id}/lists', [
        'as'   => 'elp.lists',
        'uses' => 'Elp\HeaderController@lists',
    ]);

    Route::get('create', [
        'as'   => 'elp.create',
        'uses' => 'Elp\HeaderController@create',
    ]);

    Route::post('create', [
        'as'   => 'elp.store',
        'uses' => 'Elp\HeaderController@store',
    ]);

    /*
     * Client edit
     */
    Route::get('{header_id}/edit', [
        'as'   => 'elp.edit',
        'uses' => 'Elp\HeaderController@edit',
    ]);

    Route::put('{header_id}/edit', [
        'as'   => 'elp.update',
        'uses' => 'Elp\HeaderController@update',
    ]);

    /*
     * Header result
     */
    Route::get('{header_id}/result', [
        'as'   => 'elp.result',
        'uses' => 'Elp\HeaderController@result',
    ]);

    /*
     * Header issuance edit
     */
    Route::get('issuance/{header_id}/edit', [
        'as'   => 'elp.edit.issuance',
        'uses' => 'Elp\HeaderController@editIssuance',
    ]);

    Route::put('issuance/{header_id}/edit', [
        'as'   => 'elp.update.issuance',
        'uses' => 'Elp\HeaderController@updateIssuance',
    ]);

    /*
     * Client edit issuance
     */
    Route::get('issuance/{header_id}/client/edit', [
        'as'   => 'elp.client.edit',
        'uses' => 'Elp\DetailController@clientEdit',
    ]);

    Route::put('issuance/{header_id}/client/edit', [
        'as'   => 'elp.client.update',
        'uses' => 'Elp\DetailController@clientUpdate',
    ]);

    /*
     * Header issuance
     */
    Route::get('issuance/{header_id}/show', [
        'as'   => 'elp.issuance',
        'uses' => 'Elp\HeaderController@issuance',
    ]);

    /*
     * Route Beneficiary DE
     */
    Route::get('issuance/{header_id}/beneficiary/create', [
        'as'   => 'elp.beneficiary.create',
        'uses' => 'Elp\BeneficiaryController@create',
    ]);

    Route::post('issuance/{header_id}/beneficiary/create', [
        'as'   => 'elp.beneficiary.store',
        'uses' => 'Elp\BeneficiaryController@store',
    ]);

    Route::get('issuance/{header_id}/beneficiary/edit', [
        'as'   => 'elp.beneficiary.edit',
        'uses' => 'Elp\BeneficiaryController@edit',
    ]);

    Route::put('issuance/{header_id}/beneficiary/edit', [
        'as'   => 'elp.beneficiary.update',
        'uses' => 'Elp\BeneficiaryController@update',
    ]);

    /*
     * Cancellations
     */
    Route::get('cancel', [
        'as'   => 'elp.cancel.lists',
        'uses' => 'Elp\CancellationController@lists',
    ]);

    Route::get('cancel/{header_id}/create', [
        'as'   => 'elp.cancel.create',
        'uses' => 'Elp\CancellationController@create',
    ]);

    Route::post('cancel/{header_id}/create', [
        'as'   => 'elp.cancel.store',
        'uses' => 'Elp\CancellationController@store',
    ]);

    /*
     * Pre-Approved
     */
    Route::get('pre-approved', [
        'as'   => 'elp.pre.approved.lists',
        'uses' => 'Elp\PreApprovedController@lists',
    ]);

    /*
     * Issue Quote
     */
    Route::get('issue', [
        'as'   => 'elp.issue.lists',
        'uses' => 'Elp\IssueController@lists',
    ]);
});