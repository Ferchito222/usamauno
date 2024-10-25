<?php

/*
 * Admin
 */
Route::group([ 'as' => 'adm::', 'prefix' => 'adm' ], function () {
    /*
     * Admin
     */
    Route::get('home', [
        'as'   => '',
        'uses' => 'Adm\HomeAdmController@index',
    ]);

    /*
     * Admin - User
     */
    Route::get('user/list', [
        'as'   => 'user.list',
        'uses' => 'Adm\UserAdmController@index',
    ]);

    Route::get('user/data/list', [
        'as'   => 'user.data.list',
        'uses' => 'Adm\UserAdmController@getUsers',
    ]);

    Route::get('user/import', [
        'as'   => 'user.import.start',
        'uses' => 'Adm\UserAdmController@importStart',
    ]);

    Route::post('user/import', [
        'as'   => 'user.import.complete',
        'uses' => 'Adm\UserAdmController@importComplete',
    ]);

    Route::get('user/export', [
        'as'   => 'user.export',
        'uses' => 'Adm\UserAdmController@export',
    ]);

    Route::post('user/create', [
        'as'   => 'user.store',
        'uses' => 'Adm\UserAdmController@store',
    ]);

    Route::put('user/edit', [
        'as'   => 'user.update',
        'uses' => 'Adm\UserAdmController@update',
    ]);

    Route::get('user/password/edit', [
        'as'   => 'user.password.edit',
        'uses' => 'Adm\UserAdmController@passwordEdit',
    ]);

    Route::put('user/password/edit', [
        'as'   => 'user.password.update',
        'uses' => 'Adm\UserAdmController@passwordUpdate',
    ]);

    Route::put('user/activate', [
        'as'   => 'user.activate',
        'uses' => 'Adm\UserAdmController@activate',
    ]);

    Route::put('user/password/turn-off', [
        'as'   => 'user.password.turnoff',
        'uses' => 'Adm\UserAdmController@passwordTurnOff',
    ]);

    /*
     * Admin - Assignment Wallet
     */
    Route::get('operation/assignment/wallet', [
        'as'   => 'operation.assignment.wallet.create',
        'uses' => 'Adm\UserAdmController@assignmentWalletCreate',
    ]);

    Route::get('operation/assignment/wallet/users', [
        'as'   => 'operation.assignment.wallet.users',
        'uses' => 'Adm\UserAdmController@getAssignmentWalletUsers',
    ]);

    Route::post('operation/assignment/wallet', [
        'as'   => 'operation.assignment.wallet.store',
        'uses' => 'Adm\UserAdmController@assignmentWalletStore',
    ]);

    /*
     * Admin - Emails
     */
    Route::get('email/list', [
        'as'   => 'email.list',
        'uses' => 'Adm\EmailAdmController@index',
    ]);

    Route::get('email/data/list', [
        'as'   => 'email.data.list',
        'uses' => 'Adm\EmailAdmController@getEmails',
    ]);

    Route::put('email/edit', [
        'as'   => 'email.update',
        'uses' => 'Adm\EmailAdmController@emailUpdate',
    ]);

    Route::put('email/turn-off', [
        'as'   => 'email.turnoff',
        'uses' => 'Adm\EmailAdmController@emailTurnOff',
    ]);

    Route::delete('email/delete', [
        'as'   => 'email.delete',
        'uses' => 'Adm\EmailAdmController@emailDelete',
    ]);

    /*
     * Edición de Pólizas
     */
    Route::get('operation/policy-edit', [
        'as'   => 'operation.policyEdit',
        'uses' => 'Adm\PolicyEditController@index',
    ]);

    Route::get('operation/policy-edit/data/{type}', [
        'as'   => 'operation.policyEdit.data',
        'uses' => 'Adm\PolicyEditController@getData',
    ]);

    Route::get('operation/policy-edit/search', [
        'as'   => 'operation.policyEdit.search',
        'uses' => 'Adm\PolicyEditController@search',
    ]);

    Route::get('operation/policy-edit/{code}/edit', [
        'as'   => 'operation.policyEdit.edit',
        'uses' => 'Adm\PolicyEditController@edit',
    ]);

    Route::put('operation/policy-edit/{code}/{id}/edit', [
        'as'   => 'operation.policyEdit.update',
        'uses' => 'Adm\PolicyEditController@update',
    ]);


    /*
     * Certificados Adjuntos - Eliminación
     */
    Route::get('operation/attached-certificate', [
        'as'   => 'operation.attachedCertificate',
        'uses' => 'Adm\AttachedCertificateController@index',
    ]);

    Route::get('operation/attached-certificate/lists', [
        'as'   => 'operation.attachedCertificate.lists',
        'uses' => 'Adm\AttachedCertificateController@lists',
    ]);

    Route::delete('operation/attached-certificate/{id}/delete', [
        'as'   => 'operation.attachedCertificate.delete',
        'uses' => 'Adm\AttachedCertificateController@delete',
    ]);

    /*
     * Admin - Settings
     */
    Route::get('setting/list', [
        'as'   => 'setting.list',
        'uses' => 'Adm\SettingAdmController@index',
    ]);

    Route::get('setting/data/list', [
        'as'   => 'setting.data.list',
        'uses' => 'Adm\SettingAdmController@getSettings',
    ]);

    Route::get('setting/{model}/get', [
        'as'   => 'setting.get',
        'uses' => 'Adm\SettingAdmController@getSetting',
    ]);

    Route::put('setting/edit', [
        'as'   => 'setting.update',
        'uses' => 'Adm\SettingAdmController@update',
    ]);

    /*
     * Certificado Médico
     */
    Route::get('mc', [
        'as'   => 'mc.list',
        'uses' => 'Adm\McAdmController@index',
    ]);

    Route::get('mc/data/list', [
        'as'   => 'mc.data.list',
        'uses' => 'Adm\McAdmController@getMcs',
    ]);

    Route::get('mc/{id}/questionnaires/edit', [
        'as'   => 'mc.questionnaires.edit',
        'uses' => 'Adm\McAdmController@editQuestionnaires',
    ]);

    Route::put('mc/{id}/questionnaires/edit', [
        'as'   => 'mc.questionnaires.update',
        'uses' => 'Adm\McAdmController@updateQuestionnaires',
    ]);

    /*
     * Anulaciones
     */
    Route::get('operation/cancel', [
        'as'   => 'operation.cancel',
        'uses' => 'De\CancellationController@cancel'
    ]);

    Route::get('operation/cancel/data', [
        'as'   => 'operation.cancel.data',
        'uses' => 'De\CancellationController@dataAdm'
    ]);

    Route::post('operation/cancel/{rp_id}/{header_id}/create', [
        'as'   => 'operation.cancel.store',
        'uses' => 'De\CancellationController@store'
    ]);
});