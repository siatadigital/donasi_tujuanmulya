<?php

require_once __DIR__ . '/routes-api.php';
require_once __DIR__ . '/routes-admin.php';

//
Route::get('locale/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
});
get('/', ['as' => 'page.getIndex', 'uses' => 'PageController@getIndex']);
post('/', ['as' => 'page.getIndex', 'uses' => 'PageController@getIndex']);
get('/akun', ['as' => 'page.getAkun', 'uses' => 'PageController@getAkun']);
// get('/about', ['as' => 'page.getAbout', 'uses' => 'PageController@getAbout']);
get('/syarat', ['as' => 'page.getSyarat', 'uses' => 'PageController@getSyarat']);
get('/bantuan', ['as' => 'page.getBantuan', 'uses' => 'PageController@getBantuan']);
get('/tentang', ['as' => 'page.getTentang', 'uses' => 'PageController@getTentang']);
get('/search/{keyword?}', ['as' => 'user.getSearch', 'uses' => 'ProjectController@getSearch']);
// get('/users',['as'=>'user.getIndex','uses'=>'UserController@getIndex']);
// get('/faq',['as'=>'page.getFaq','uses'=>'PageController@getFaq']);
// get('/kebijakan',['as'=>'page.getKebijakan','uses'=>'PageController@getKebijakan']);
// get('/peraturan',['as'=>'page.getPeraturan','uses'=>'PageController@getPeraturan']);
// get('/tutorial',['as'=>'page.getTutorial','uses'=>'PageController@getTutorial']);
// get('/resiko',['as'=>'page.getResiko','uses'=>'PageController@getResiko']);
// get('/visi-misi',['as'=>'page.getVisiMisi','uses'=>'PageController@getVisiMisi']);

// Midtrans
Route::post('/midtrans/finish', function () {
    return redirect()->route('welcome');
})->name('midtrans.finish');
Route::post('/midtrans/store/{type}', 'MidtransController@submitMidtrans')->name('midtrans.store');
Route::post('/notification/handler', 'MidtransController@notificationHandler')->name('notification.handler');
// Route::post('/notification/moota', 'MidtransController@notificationMoota')->name('notification.moota');
// Route::post('/notification/xendit', 'MidtransController@notificationXendit')->name('notification.xendit');
// Route::post('/notification/duitku', 'MidtransController@notificationDuitku')->name('notification.duitku');
// Route::post('/notification/doku', 'MidtransController@notificationDoku')->name('notification.doku');
// Route::post('/notification/muamalat', 'MidtransController@notificationMuamalat')->name('notification.muamalat');

// get('/sendmail',['as'=>'sendmail','uses'=>'Admin\UserController@sendAllNotification2']);

// cek status donasi
// get('/cek-donasi', ['as' => 'project.getDonasi', 'uses' => 'ProjectController@getCheckDonasi']);
// post('/cek-donasi', ['as' => 'project.postDonasi', 'uses' => 'ProjectController@postCheckDonasi']);

Route::group(['prefix' => 'events'], function () {
    get('/', ['as' => 'event.getIndex', 'uses' => 'EventController@getIndex']);
    get('/create', ['middleware' => 'auth', 'as' => 'event.getCreate', 'uses' => 'EventController@getCreate']);
    post('/create', ['middleware' => 'auth', 'as' => 'event.postCreate', 'uses' => 'EventController@postCreate']);
    post('/registration', ['middleware' => 'auth', 'as' => 'event.registration', 'uses' => 'EventController@registration']);
    get('/{slug}', ['as' => 'event.getShow', 'uses' => 'EventController@getShow']);
    get('/{slug}/edit', ['middleware' => 'auth', 'as' => 'event.getEdit', 'uses' => 'EventController@getEdit']);
    put('/{slug}/update', ['middleware' => 'auth', 'as' => 'event.putEdit', 'uses' => 'EventController@putEdit']);
    get('/{slug}/delete', ['as' => 'event.destroy', 'uses' => 'EventController@destroy']);
});

Route::group(['prefix' => 'blogs'], function () {
    get('/', ['as' => 'blog.getIndex', 'uses' => 'BlogController@getIndex']);
    get('/create', ['middleware' => 'auth', 'as' => 'blog.getCreate', 'uses' => 'BlogController@getCreate']);
    post('/create', ['middleware' => 'auth', 'as' => 'blog.postCreate', 'uses' => 'BlogController@postCreate']);
    get('/{slug}', ['as' => 'blog.getShow', 'uses' => 'BlogController@getShow']);
    get('/{slug}/edit', ['middleware' => 'auth', 'as' => 'blog.getEdit', 'uses' => 'BlogController@getEdit']);
    put('/{slug}/update', ['middleware' => 'auth', 'as' => 'blog.putEdit', 'uses' => 'BlogController@putEdit']);
    get('/{slug}/delete', ['as' => 'blog.destroy', 'uses' => 'BlogController@destroy']);
});

//Projects
Route::group(['prefix' => 'projects'], function () {
    get('/', ['as' => 'project.getIndex', 'uses' => 'ProjectController@getIndex']);
    get('/popular', ['as' => 'project.getPopular', 'uses' => 'ProjectController@getPopular']);
    get('/create', ['as' => 'project.getCreate', 'uses' => 'ProjectController@getCreate']);
    post('/create', ['as' => 'project.postCreate', 'uses' => 'ProjectController@postCreate']);
    get('/update', ['middleware' => 'auth', 'as' => 'project.getUpdate', 'uses' => 'ProjectController@getUpdate']);
    post('/update', ['middleware' => 'auth', 'as' => 'project.postUpdate', 'uses' => 'ProjectController@postUpdate']);
    get('/{id}/fundraiser', ['middleware' => 'auth', 'as' => 'project.getFundraiser', 'uses' => 'ProjectController@getFundraiser']);
    post('/{id}/fundraiser', ['middleware' => 'auth', 'as' => 'project.postFundraiser', 'uses' => 'ProjectController@postFundraiser']);
    get('/update/{id}', ['as' => 'project.showUpdate', 'uses' => 'ProjectController@showUpdate']);
    get('/update/{id}/edit', ['as' => 'project.getEditUpdate', 'uses' => 'ProjectController@getEditUpdate']);
    post('/update/{id}/edit', ['as' => 'project.postEditUpdate', 'uses' => 'ProjectController@postEditUpdate']);
    get('/{slug}', ['as' => 'project.getShow', 'uses' => 'ProjectController@getShow']);
    // get('/{slug}/support', ['as' => 'project.getSupport', 'uses' => 'ProjectController@getSupport']);
    // post('/{slug}/support', ['as' => 'project.postSupport', 'uses' => 'ProjectController@postSupport']);
    // get('/{slug}/supported', ['as' => 'project.supportThankyou', 'uses' => 'ProjectController@supportThankyou']);
    get('/{slug}/edit', ['as' => 'project.getEdit', 'uses' => 'ProjectController@getEdit']);
    put('/{slug}/edit', ['as' => 'project.putEdit', 'uses' => 'ProjectController@putEdit']);
    // put('/{slug}/confirm-payment', ['as' => 'project.putConfirmPayment', 'uses' => 'ProjectController@putConfirmPayment']);
    // delete('/{slug}/delete',['as'=>'project.destroy','uses'=>'ProjectController@destroy']);
    get('/{slug}/withdraw', ['as' => 'project.getWithdraw', 'uses' => 'ProjectController@getWithdraw']);
    post('/{slug}/withdraw', ['as' => 'project.postWithdraw', 'uses' => 'ProjectController@postWithdraw']);
});
get('/{slug}', ['as' => 'project.newGetShow', 'uses' => 'ProjectController@getShow']);

// Password reset link request routes...
get('password/email', ['as' => 'password.getEmail', 'uses' => 'Auth\PasswordController@getEmail']);
post('password/email', ['as' => 'password.postEmail', 'uses' => 'Auth\PasswordController@postEmail']);

// Password reset routes...
get('password/reset/{token}', ['as' => 'password.getReset', 'uses' => 'Auth\PasswordController@getReset']);
post('password/reset', ['as' => 'password.postReset', 'uses' => 'Auth\PasswordController@postReset']);

//Route for auth
get('auth/login', ['as' => 'auth.getLogin', 'uses' => 'Auth\AuthController@getLogin']);
post('auth/login', ['as' => 'auth.postLogin', 'uses' => 'Auth\AuthController@postLogin']);
get('auth/register', ['as' => 'auth.getRegister', 'uses' => 'Auth\AuthController@getRegister']);
post('auth/register', ['as' => 'auth.postRegister', 'uses' => 'Auth\AuthController@postRegister']);
get('auth/logout', ['as' => 'auth.getLogout', 'uses' => 'Auth\AuthController@getLogout']);

// facebook connect
// get('auth/facebook', ['as' => 'auth.connectFacebook', 'uses' => 'Auth\AuthController@connectFacebook']);
// get('auth/facebook/callback', ['as' => 'auth.callbackFacebook', 'uses' => 'Auth\AuthController@callbackFacebook']);

// twitter connect
// get('auth/twitter', ['as' => 'auth.connectTwitter', 'uses' => 'Auth\AuthController@connectTwitter']);
// get('auth/twitter/callback', ['as' => 'auth.callbackTwitter', 'uses' => 'Auth\AuthController@callbackTwitter']);

Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');

//Route for user
get('/user/report-affiliate', ['middleware' => 'auth', 'as' => 'user.getReportAffiliate', 'uses' => 'UserController@getReportAffiliate']);
get('/user/{username}', ['as' => 'user.getShow', 'uses' => 'UserController@getShow']);
post('/user/{username}/setting', ['middleware' => 'auth', 'as' => 'user.putSetting', 'uses' => 'UserController@putSetting']);
get('/user/{username}/projects', ['as' => 'user.getProjects', 'uses' => 'UserController@getProjects']);
get('/user/{username}/support', ['as' => 'user.getSupport', 'uses' => 'UserController@getSupport']);

get('/verify/{encrypt}', ['middleware' => 'auth', 'as' => 'user.getVerified', 'uses' => 'UserController@getVerified']);
get('/user/{username}/validate', ['middleware' => 'auth', 'as' => 'user.getValidate', 'uses' => 'UserController@getValidate']);
post('/user/{username}/validate', ['middleware' => 'auth', 'as' => 'user.postValidate', 'uses' => 'UserController@postValidate']);
get('/user/{username}/setting/profile', ['middleware' => 'auth', 'as' => 'user.getSettingProfile', 'uses' => 'UserController@getSettingProfile']);
get('/user/{username}/setting/social', ['middleware' => 'auth', 'as' => 'user.getSettingSocial', 'uses' => 'UserController@getSettingSocial']);
get('/user/{username}/setting/security', ['middleware' => 'auth', 'as' => 'user.getSettingSecurity', 'uses' => 'UserController@getSettingSecurity']);
get('/user/{username}/setting', ['middleware' => 'auth', 'as' => 'user.getSetting', 'uses' => 'UserController@getSetting']);
