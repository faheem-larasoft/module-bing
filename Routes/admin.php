<?php

Route::get('/controller/bing', 'IndexController@index')->name('admin_bing');
Route::get('/controller/bing/campaign/{id}', 'IndexController@campaign')->name('admin_bing_campaign');
Route::get('/controller/bing/adgroup/{id}', 'IndexController@adgroup')->name('admin_bing_adgroup');