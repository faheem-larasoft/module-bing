<?php

#Route::post('/controller/maps/tradespeople', 'Maps\TradespeopleController@filter');
#Route::get('/controller/maps/tradespeople', 'Maps\TradespeopleController@index')->name('admin_maps_tradespeople');

Route::get('/controller/adwords', 'Campaigns\CampaignController@index')->name('admin_adwords');
Route::post('/controller/adwords', 'Campaigns\CampaignController@status')->middleware('csrf');

Route::get('/controller/adwords/campaign/{campaign}', 'Campaigns\CampaignController@campaign')->name('admin_adwords_campaign');
Route::get('/controller/adwords/adgroup/{adgroup}', 'Adgroups\AdgroupController@view')->name('admin_adwords_adgroup');

Route::get('/controller/adwords/ad/pause/{ad}', 'AdController@pause')->name('admin_adwords_pause_ad');
Route::get('/controller/adwords/ad/enable/{ad}', 'AdController@enable')->name('admin_adwords_enable_ad');
Route::post('/controller/adwords/clonead/{adgroup}', 'AdController@cloneAd')->name('admin_adwords_clone_ad');
#Route::get('/controller/adwords/campaign/{campaign}/targets', 'CampaignTargetController@index')->name('admin_adwords_campaign_targets');

Route::get('/controller/adwords/qpt', 'QptController@index')->name('admin_adwords_qpt');

#Route::get('/controller/draw/{postcode}/{areas}', 'TargetingAreasController@draw')->name('admin_draw');

Route::get('/controller/adwords/adgroups', 'Adgroups\AdgroupController@index')->name('admin_adwords_adgroups');

Route::post('/controller/adwords/hourly', 'HourlyController@setCampaign');
Route::get('/controller/adwords/hourly', 'HourlyController@view')->name('admin_adwords_hourly');
Route::get('/controller/adwords/daily', 'DailyController@view')->name('admin_adwords_daily');
Route::get('/controller/adwords/device', 'DeviceController@index')->name('admin_adwords_device');

Route::post('/controller/adwords/regions', 'RegionController@save')->name('admin_adwords_regions_save');
Route::get('/controller/adwords/regions', 'RegionController@view')->name('admin_adwords_regions');
