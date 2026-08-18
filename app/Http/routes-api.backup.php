<?php

Route::group(['prefix' => 'api/v1', 'namespace' => 'Api\v1'], function () {

    Route::group(['prefix' => 'media'], function () {
        post('/upload', ['as' => 'api.media.upload', 'uses' => 'MediaController@upload', 'middleware' => 'ajax']);
        delete('/{id}/delete', ['as' => 'api.media.delete', 'uses' => 'MediaController@delete', 'middleware' => 'ajax']);
    });

    Route::group(['prefix' => 'user'], function () {
        post('/check-username', ['as' => 'api.user.checkUsername', 'uses' => 'UserController@checkUsername', 'middleware' => 'ajax']);
        post('/update-setting', ['as' => 'api.user.updateSetting', 'uses' => 'UserController@updateSetting', 'middleware' => 'ajax']);
        post('/resetPassword', ['as' => 'api.user.resetPassword', 'uses' => 'UserController@resetPassword', 'middleware' => 'ajax']);
    });

    Route::post('/getkota', ['as' => 'api.kota.getkota', 'uses' => 'KotaController@getKotaByProvinsi', 'middleware' => 'ajax']);

    Route::group(['prefix' => 'static'], function () {

    	get('/location', ['as' => 'api.static.getLocation', 'uses' => 'StaticController@getLocation']);

    });
    Route::post('/check-expired', ['as' => 'api.check.expired', 'uses' => 'StaticController@checkExpiredTransaction']);
});
