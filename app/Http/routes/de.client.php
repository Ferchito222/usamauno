<?php

/*
 *  Search Client
 */
Route::get('{rp_id}/client/search/{header_id?}', [
    'as'   => 'client.search',
    'uses' => 'Client\ClientController@search'
]);

Route::get('test/ws/{type}/{code_client}', [
    'as'   => 'client.new.conection',
    'uses' => 'Client\ClientController@newConection'
]);

Route::get('list/ws/clients', [
    'as'   => 'client.ws.list',
    'uses' => 'Client\ClientController@getClients'
]);

/*
 * Route Client DE
*/
Route::group([ 'prefix' => 'de/{rp_id}/{header_id}' ], function () {
    /*
     * Client create
     */
    Route::get('client/create/{client_id?}', [
        'as'   => 'de.detail.create',
        'uses' => 'De\DetailController@create'
    ]);

    Route::post('client/create', [
        'as'   => 'de.detail.store',
        'uses' => 'De\DetailController@store'
    ]);

    /*
     * Client edit
     */
    Route::get('client/edit/{detail_id}', [
        'as'   => 'de.detail.edit',
        'uses' => 'De\DetailController@edit'
    ]);

    Route::put('client/edit/{detail_id}', [
        'as'   => 'de.detail.update',
        'uses' => 'De\DetailController@update'
    ]);

    /*
     * Client validate
     */
    Route::get('client/{detail_id}/validate', [
        'as'   => 'de.client.validate',
        'uses' => 'De\DetailController@validateClient'
    ]);

    /*
     * Client edit complementary data
     */
    Route::get('edit/detail/edit/{detail_id}/{ref}', [
        'as'   => 'de.detail.i.edit',
        'uses' => 'De\DetailController@editIssue'
    ]);

    Route::put('edit/detail/edit/{detail_id}/{ref}', [
        'as'   => 'de.detail.i.update',
        'uses' => 'De\DetailController@updateIssue'
    ]);

    /*
     * Route Beneficiary DE
     */
    Route::get('beneficiary/create/{detail_id}', [
        'as'   => 'de.beneficiary.create',
        'uses' => 'De\BeneficiaryController@create'
    ]);

    Route::post('beneficiary/create/{detail_id}', [
        'as'   => 'de.beneficiary.store',
        'uses' => 'De\BeneficiaryController@store'
    ]);

    Route::get('beneficiary/edit/{detail_id}', [
        'as'   => 'de.beneficiary.edit',
        'uses' => 'De\BeneficiaryController@edit'
    ]);

    Route::put('beneficiary/edit/{detail_id}', [
        'as'   => 'de.beneficiary.update',
        'uses' => 'De\BeneficiaryController@update'
    ]);

    /*
     * Detail Balance DE
     */
    Route::get('balance/edit/{detail_id}', [
        'as'   => 'de.detail.balance.edit',
        'uses' => 'De\DetailController@editBalance'
    ]);

    Route::put('balance/edit/{detail_id}', [
        'as'   => 'de.detail.balance.update',
        'uses' => 'De\DetailController@updateBalance'
    ]);

    /* ***********************************************
     * Vida Sub Product list
     */
    Route::get('vi/{sp_id}', [
        'as'   => 'de.vi.sp.list',
        'uses' => 'De\HeaderController@viSPList'
    ]);

    /*
     * Vida Sub Product save list
     */
    Route::post('vi/{sp_id}', [
        'as'   => 'de.vi.sp.list.store',
        'uses' => 'De\HeaderController@viSPListStore'
    ]);

    /*
     * Sub Product Create
     */
    Route::get('vi/{sp_id}/create', [
        'as'   => 'de.vi.sp.create',
        'uses' => 'Vi\HeaderController@createSubProduct'
    ]);

    /*
     * Sub Product Create
     */
    Route::post('vi/{sp_id}/create', [
        'as'   => 'de.vi.sp.store',
        'uses' => 'Vi\HeaderController@storeSubProduct'
    ]);
});