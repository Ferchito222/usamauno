<?php
/*
 * Route Issuance Vd
 */
 Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => 'is_admin'], function () {
        Route::group(['prefix' => 'vd/{rp_id}'], function () {
            
            Route::get('create', [
                'as' => 'vd.create',
                'uses' => 'Vd\DetailController@create'
            ]);

            Route::post('store', [
                'as' => 'vd.store',
                'uses' => 'Vd\DetailController@store'
            ]);

            Route::get('{header_id}/list', [
                'as' => 'vd.client.list',
                'uses' => 'Vd\HeaderController@lists'
            ]);

            Route::post('{header_id}/tomador', [
                'as'   => 'vd.store.tomador',
                'uses' => 'Vd\DetailController@storeTomador'
            ]);

            /*
             * Client edit
             */
            Route::get('{header_id}/client/edit/{detail_id}', [
                'as' => 'vd.detail.edit',
                'uses' => 'Vd\DetailController@edit'
            ]);

            Route::put('{header_id}/client/edit/{detail_id}', [
                'as' => 'vd.detail.update',
                'uses' => 'Vd\DetailController@update'
            ]);

            /*
             * Header result on Quote
             */
            Route::get('{header_id}/result', [
                'as' => 'vd.result',
                'uses' => 'Vd\HeaderController@result'
            ]);

            /*
             * Header edit
             */
            Route::get('{header_id}/edit', [
                'as' => 'vd.edit',
                'uses' => 'Vd\HeaderController@edit'
            ]);

            Route::put('edit/{header_id}', [
                'as' => 'vd.update',
                'uses' => 'Vd\HeaderController@update'
            ]);

            /*
             * Header issuance
             */
            Route::get('issue/{header_id}', [
                'as' => 'vd.issue',
                'uses' => 'Vd\HeaderController@issuance'
            ]);

            Route::get('issuance/{header_id}/result', [
                'as' => 'vd.show.issuance',
                'uses' => 'Vd\HeaderController@showIssuance'
            ]);

            /*
             * Pre-Approved
             */
            Route::get('pre-approved', [
                'as' => 'vd.pre.approved.lists',
                'uses' => 'Vd\PreApprovedController@lists'
            ]);

            /*
             * Issue Quote
             */
            Route::get('issue/{guest?}', [
                'as' => 'vd.issue.lists',
                'uses' => 'Vd\IssueController@lists'
            ]);

            /*
             * Cancellations
             */
            Route::get('cancel', [
                'as' => 'vd.cancel.lists',
                'uses' => 'Vd\CancellationController@lists'
            ]);

            Route::get('cancel/{header_id}/create', [
                'as' => 'vd.cancel.create',
                'uses' => 'Vd\CancellationController@create'
            ]);

            Route::post('cancel/{header_id}/create', [
                'as' => 'vd.cancel.store',
                'uses' => 'Vd\CancellationController@store'
            ]);
        });

        Route::group(['prefix' => 'vd/{rp_id}/{header_id}'], function () {

            Route::get('beneficiary/create/{detail_id}', [
                'as' => 'vd.beneficiary.create',
                'uses' => 'Vd\BeneficiaryController@create'
            ]);

            Route::post('beneficiary/create/{detail_id}', [
                'as' => 'vd.beneficiary.store',
                'uses' => 'Vd\BeneficiaryController@store'
            ]);

            Route::get('beneficiary/edit/{detail_id}/{beneficiary_id}', [
                'as' => 'vd.beneficiary.edit',
                'uses' => 'Vd\BeneficiaryController@edit'
            ]);

            Route::put('beneficiary/edit/{detail_id}/{beneficiary_id}', [
                'as' => 'vd.beneficiary.update',
                'uses' => 'Vd\BeneficiaryController@update'
            ]);

            Route::delete('beneficiary/{beneficiary_id}', [
                'as' => 'vd.beneficiary.destroy',
                'uses' => 'Vd\BeneficiaryController@destroy'
            ]);

            /*
             * Client edit complementary data
             */
            Route::get('edit/detail/edit/{detail_id}', [
                'as' => 'vd.detail.i.edit',
                'uses' => 'Vd\DetailController@editIssue'
            ]);

            Route::put('edit/detail/edit/{detail_id}', [
                'as' => 'vd.detail.i.update',
                'uses' => 'Vd\DetailController@updateIssue'
            ]);
        });
    });
 });