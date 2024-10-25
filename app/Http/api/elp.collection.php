<?php

/*
 * Collections
 */
Route::get('collection/{code}/export', [
    'as'   => 'elp.collection.export',
    'uses' => 'API\ElpCollectionController@export',
]);

Route::get('collection/{code}/import-confirm', [
    'as'   => 'elp.collection.import.confirm',
    'uses' => 'API\ElpCollectionController@importConfirm',
]);

Route::get('collection/{code}/import-production', [
    'as'   => 'elp.collection.import.production',
    'uses' => 'API\ElpCollectionController@importProduction',
]);