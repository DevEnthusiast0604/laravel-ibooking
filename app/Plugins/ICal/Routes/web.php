<?php
$namespace = 'App\Plugins\ICal\Controllers';

Route::group([
    'namespace' => $namespace,
    'middleware' => ['web', 'locale'],
], function () {
    Route::get('{post_type}/{id}/ical.ics' , 'ICalController@getICal');
});