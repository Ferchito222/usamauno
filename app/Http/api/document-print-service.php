<?php
Route::get('print/{token}', [
    'as'   => 'document.print.service',
    'uses' => 'API\DocumentPrintController@printing',
]);