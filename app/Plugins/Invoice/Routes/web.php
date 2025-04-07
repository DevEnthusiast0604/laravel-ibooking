<?php
$namespace = 'App\Plugins\Invoice\Controllers';

Route::group([
    'namespace' => $namespace,
    'middleware' => ['web', 'auth', 'locale'],
], function () {
    Route::get('booking/invoice/{order_token}' , 'InvoiceController@getInvoice');
});