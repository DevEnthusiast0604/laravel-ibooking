<?php
if ( ! defined( 'GMZPATH' ) ) { exit; }
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */
$namespace = 'App\Plugins\SecurionpayGateway\Controllers';

Route::group([
    'namespace' => $namespace,
    'middleware' => ['web', 'auth', 'locale'],
], function () {
    Route::post('payment/securionpay/success', 'Securionpay@securionpaySuccessAction');
    Route::post('payment/securionpay/error', 'Securionpay@securionpayErrorAction');
});