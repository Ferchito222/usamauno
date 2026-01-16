<?php

/*
 * Route Certificates
 */
Route::group([ 'prefix' => '{code}/{rp_id}' ], function () {

    /*
     * Show
     */
    Route::get('{header_id}/type/{type}/format/{format}', [
        'as'   => 'certificate.show',
        'uses' => 'CertificateController@show'
    ]);

    Route::get('template/{id}/type/{type}/format/{format}', [
        'as'   => 'template.pdf.show',
        'uses' => 'CertificateController@templatePdfs'
    ]);

    Route::post('{header_id}/type/{type}', [
        'as'   => 'certificate.attached',
        'uses' => 'CertificateController@attached'
    ]);
});
