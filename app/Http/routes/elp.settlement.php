<?php
/**
 * Created by PhpStorm.
 * User: jvera
 * Date: 23/12/2016
 * Time: 04:14 PM
 */

Route::get('settlement', 'Elp\SettlementController@month');

Route::get('data_agency', 'Elp\SettlementController@agency');

Route::get('data_city', 'Elp\SettlementController@city');

Route::get('data_client', 'Elp\CiaSettlementController@client');

Route::get('data_not_client', 'Elp\CiaSettlementController@not_client');

Route::get('data_anul_client', 'Elp\CiaSettlementController@anul_client');