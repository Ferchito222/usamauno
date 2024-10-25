<?php

/*
 * Route Reports
 */
Route::group(['prefix' => '/'], function () {
    # ruta desgravamen

    Route::get('report/general/{id_comp}', [
        'as'   => 'report.report_general',
        'uses' => 'Report\ReportController@general',
    ]);

    Route::post('report/general/{id_comp}', [
        'as'   => 'report.report_general_result',
        'uses' => 'Report\ReportController@general',
    ]);

    Route::get('report/general_emitido/{id_comp}', [
        'as'   => 'report.report_general_emitido',
        'uses' => 'Report\ReportController@general_emitido',
    ]);

    Route::post('report/general_emitido/{id_comp}', [
        'as'   => 'report.report_general_result_emitido',
        'uses' => 'Report\ReportController@general_emitido',
    ]);

    Route::get('report/cotizacion/{id_comp}', [
        'as'   => 'report.report_cotizacion',
        'uses' => 'Report\ReportController@cotizacion',
    ]);

    Route::post('report/cotizacion/{id_comp}', [
        'as'   => 'report.report_cotizacion_result',
        'uses' => 'Report\ReportController@cotizacion',
    ]);

    /*
     * Reportes Generales
     */
    Route::get('report/{code}/{rp_id}/master/{type}/{format?}', [
        'as'   => 'report.master',
        'uses' => 'Report\ReportGeneralController@master',
    ]);

    /*
     * Reportes Cobranzas
     */
    Route::get('collection/report/{code}/{rp_id}/master/{type}/{format?}', [
        'as'   => 'report.master.collection',
        'uses' => 'Report\ReportCollectionController@master',
    ]);
    
    /*
     * Reportes Graficos
     */
    Route::get('report/graphic', [
        'as'   => 'report.graphic',
        'uses' => 'Report\ReportGeneralController@graphic',
    ]);

    Route::get('report/graphic/data', [
        'as'   => 'report.graphic.data',
        'uses' => 'Report\ReportGeneralController@dataGraphic',
    ]);

});