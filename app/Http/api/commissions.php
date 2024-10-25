<?php
/*
 *
 */
Route::get('extra/{code}/commissions', [
    'as'   => 'extra.commission.panel',
    'uses' => 'API\CommissionController@panel',
]);

Route::get('extra/{code}/commissions/report', [
    'as'   => 'extra.commission.report',
    'uses' => 'API\CommissionController@report',
]);

Route::post('extra/{code}/commissions/report', [
    'as'   => 'extra.commission.export',
    'uses' => 'API\CommissionController@report',
]);