<?php

Route::post('/api/oauth/token', 'Api\v1\BcaController@oauthToken');
Route::post('/va/bills', 'Api\v1\BcaController@vaBills');
Route::post('/va/payments', 'Api\v1\BcaController@vaPayments');


Route::group(['prefix' => 'api/v1'], function () {

    Route::group(['prefix' => 'media'], function () {
        post('/upload', ['as' => 'api.media.upload', 'uses' => 'Api\v1\MediaController@upload', 'middleware' => 'ajax']);
        delete('/{id}/delete', ['as' => 'api.media.delete', 'uses' => 'Api\v1\MediaController@delete', 'middleware' => 'ajax']);
    });

    Route::group(['prefix' => 'user'], function () {
        post('/check-username', ['as' => 'api.user.checkUsername', 'uses' => 'Api\v1\UserController@checkUsername', 'middleware' => 'ajax']);
        post('/update-setting', ['as' => 'api.user.updateSetting', 'uses' => 'Api\v1\UserController@updateSetting', 'middleware' => 'ajax']);
        post('/resetPassword', ['as' => 'api.user.resetPassword', 'uses' => 'Api\v1\UserController@resetPassword', 'middleware' => 'ajax']);
    });

    Route::post('/getkota', ['as' => 'api.kota.getkota', 'uses' => 'Api\v1\KotaController@getKotaByProvinsi', 'middleware' => 'ajax']);

    Route::group(['prefix' => 'static'], function () {

        get('/location', ['as' => 'api.static.getLocation', 'uses' => 'Api\v1\@getLocation']);
    });
    Route::post('/check-expired', ['as' => 'api.check.expired', 'uses' => 'Api\v1\StaticController@checkExpiredTransaction']);

    Route::get('/campaigns/sync', ['uses' => 'Api\v1\CampaignListController@list']);
});
