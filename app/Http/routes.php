<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
 * Authentication / Cashiers
 */
require 'routes/auth.php';

Route::get('mail-test', [
    'as'   => 'mail.test',
    'uses' => 'Test@mail'
]);


Route::group([ 'middleware' => [ 'auth' ] ], function () {
    /*
     * Migrations
     */
    require 'routes/migration.php';

    Route::group([ 'middleware' => 'is_admin' ], function () {
        /*
         * Home
         */
        Route::get('/', function () {
            return redirect()->route('home');
        });

        Route::get('home', [
            'as'   => 'home',
            'uses' => 'HomeController@index',
        ]);

        /*
         * Users
         */
        require 'routes/user.php';

        /*
         * Header DE
         */
        require 'routes/de.issuance.php';

        require 'routes/de.client.php';

        require 'routes/de.question.php';

        require 'routes/report.php';

        /*
         * Header TD
         */
        require 'routes/td.issuance.php';
        /*
         * Header AU
         */
        require 'routes/au.issuance.php';
        /*
         * Header EMA
         */
        require 'routes/ema.issuance.php';
        /*
         * Header RMA
         */
        require 'routes/rma.issuance.php';
        /*
         * Header ELP
         */
        require 'routes/elp.issuance.php';
        /*
         * Header EB
         */
        require 'routes/eb.issuance.php';
        
        /*
         * Header TJ
         */
        $GLOBALS['prod'] = 'tj';
        require_once 'routes/tj.issuance.php';
        
        /*
         * Edit data Policy
         */
        require 'routes/data.policy.php';

        /*
         * RUTA UNICA PRODUCTOS MODAL
         */
        Route::get('certificate/{id_retailer_product}/{id_header}/{text}/{type}', [
            'as'   => 'create_modal_slip',
            'uses' => 'ModalCSController@ajax_modal',
        ]);

	/**
         * Reporte grafico
         */
        Route::get('graphic/report/{format}', [
            'as'   => 'graphic.report',
            'uses' => 'Report\GraphicReportController@graphic',
        ]);
    });

    /*
     * Administrator
     */
    Route::group([ 'middleware' => 'is_user' ], function () {
        /*
         * Admin
         */
        require_once 'routes/adm.php';
    });

    Route::get('files/{file}', [
        'as'   => 'de.attach.file',
        'uses' => 'De\HeaderController@getAttachFile',
    ]);
    Route::get('files_au/{file}', [
        'as'   => 'au.attach.file',
        'uses' => 'Au\HeaderController@getAttachFile',
    ]);
    Route::get('files_td/{file}', [
        'as'   => 'td.attach.file',
        'uses' => 'Td\HeaderController@getAttachFile',
    ]);
    Route::get('files_ema/{file}', [
        'as'   => 'ema.attach.file',
        'uses' => 'Ema\HeaderController@getAttachFile',
    ]);
    Route::get('files_rma/{file}', [
        'as'   => 'rma.attach.file',
        'uses' => 'Rma\HeaderController@getAttachFile',
    ]);
    Route::get('files_tj/{file}', [
        'as'   => 'tj.attach.file',
        'uses' => 'Tj\HeaderController@getAttachFile',
    ]);
});

/*
 * Certificate
 */
Route::group([ 'middleware' => 'logged_in' ], function () {
    require 'routes/certificate.php';
});

/*
 * APIs
 */
require 'api/elp.collection.php';

/*
 * Commissions
 */
require 'api/commissions.php';


Route::get('{rp_id}/facultative/{id}/mc/show', [
    'as'   => 'de.fa.mc.show',
    'uses' => 'De\MedicalCertificateController@show',
]);

require 'routes/elp.settlement.php';

require 'routes/vd.issuance.php';

/*
 * api document print
 */
require 'api/document-print-service.php';

/**
 * route credit card, external client
 */
Route::get('cliente_tarjetas', [
    'as'   => 'client.card',
    'uses' => 'Tj\ClientCardController@clientCard'
]);

Route::post('cliente_tarjetas/record', [
    'as'   => 'client.card.record',
    'uses' => 'Tj\ClientCardController@clientCardRecord'
]);

Route::get('cliente_tarjetas/{code}/{rp_id}/{header_id}/type/{type}/format/{format}', [
    'as'   => 'certificate.show.client',
    'uses' => 'CertificateController@show'
]);
