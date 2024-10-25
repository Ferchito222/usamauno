<?php

/*
 * Route Issuance EB
 */
Route::group([ 'prefix' => 'eb/{rp_id}' ], function () {

    /*
     * Client create
     */
    Route::get('{header_id}/lists', [
        'as'   => 'eb.lists',
        'uses' => 'Eb\HeaderController@lists',
    ]);

    Route::get('create', [
        'as'   => 'eb.create',
        'uses' => 'Eb\HeaderController@create',
    ]);

    Route::post('create', [
        'as'   => 'eb.store',
        'uses' => 'Eb\HeaderController@store',
    ]);

    /*
     * Client edit
     */
    Route::get('{header_id}/edit', [
        'as'   => 'eb.edit',
        'uses' => 'Eb\HeaderController@edit',
    ]);

    Route::put('{header_id}/edit', [
        'as'   => 'eb.update',
        'uses' => 'Eb\HeaderController@update',
    ]);

    /*
     * Header result
     */
    Route::get('{header_id}/result', [
        'as'   => 'eb.result',
        'uses' => 'Eb\HeaderController@result',
    ]);

    /*
     * Header issuance edit
     */
    Route::get('issuance/{header_id}/edit', [
        'as'   => 'eb.edit.issuance',
        'uses' => 'Eb\HeaderController@editIssuance',
    ]);

    Route::put('issuance/{header_id}/edit', [
        'as'   => 'eb.update.issuance',
        'uses' => 'Eb\HeaderController@updateIssuance',
    ]);

    /*
     * Client edit issuance
     */
    Route::get('issuance/{header_id}/client/edit', [
        'as'   => 'eb.client.edit',
        'uses' => 'Eb\DetailController@clientEdit',
    ]);

    Route::put('issuance/{header_id}/client/edit', [
        'as'   => 'eb.client.update',
        'uses' => 'Eb\DetailController@clientUpdate',
    ]);

    /*
     * Order
     */
    Route::get('issuance/{header_id}/order', [
        'as'   => 'eb.order',
        'uses' => 'Eb\HeaderController@getOrder',
    ]);

    /*
     * Header issuance
     */
    Route::get('issuance/{header_id}/show', [
        'as'   => 'eb.issuance',
        'uses' => 'Eb\HeaderController@issuance',
    ]);

    /*
     * Route Beneficiary DE
     */
    Route::get('issuance/{header_id}/beneficiary/create', [
        'as'   => 'eb.beneficiary.create',
        'uses' => 'Eb\BeneficiaryController@create',
    ]);

    Route::post('issuance/{header_id}/beneficiary/create', [
        'as'   => 'eb.beneficiary.store',
        'uses' => 'Eb\BeneficiaryController@store',
    ]);

    Route::get('issuance/{header_id}/beneficiary/edit', [
        'as'   => 'eb.beneficiary.edit',
        'uses' => 'Eb\BeneficiaryController@edit',
    ]);

    Route::put('issuance/{header_id}/beneficiary/edit', [
        'as'   => 'eb.beneficiary.update',
        'uses' => 'Eb\BeneficiaryController@update',
    ]);

    /*
     * Cancellations
     */
    Route::get('cancel', [
        'as'   => 'eb.cancel.lists',
        'uses' => 'Eb\CancellationController@lists',
    ]);

    Route::get('cancel/{header_id}/create', [
        'as'   => 'eb.cancel.create',
        'uses' => 'Eb\CancellationController@create',
    ]);

    Route::post('cancel/{header_id}/create', [
        'as'   => 'eb.cancel.store',
        'uses' => 'Eb\CancellationController@store',
    ]);

    /*
     * Pre-Approved
     */
    Route::get('pre-approved', [
        'as'   => 'eb.pre.approved.lists',
        'uses' => 'Eb\PreApprovedController@lists',
    ]);

    /*
     * Issue Quote
     */
    Route::get('issue', [
        'as'   => 'eb.issue.lists',
        'uses' => 'Eb\IssueController@lists',
    ]);
});