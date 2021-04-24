<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/intro','LandingpageController@index');
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/install/check-db', 'HomeController@checkConnectDatabase');

Route::get('/update', function (){
    return redirect('/');
});

Route::get('/plan','HomeController@plan')->name('plan');// Pla

Route::group(['prefix'=>'admin','middleware' => ['auth','dashboard']], function() {
    Route::get('/plan','HomeController@store_plan')->name('admin.plan');// Pla
    Route::post('/plan','HomeController@store_plan_real')->name('admin.plan');// Pla
});

//Cache
Route::get('/clear', function() {
    Artisan::call('optimize:clear');
    return "Cleared!";
});

//Login
Auth::routes();
//Custom User Login and Register
Route::post('register/','\Modules\User\Controllers\UserController@userRegister')->name('auth.register');
Route::post('login/','\Modules\User\Controllers\UserController@userLogin')->name('auth.login');
Route::post('logout/','\Modules\User\Controllers\UserController@logout')->name('auth.logout');
// Social Login
Route::get('social-login/{provider}', 'Auth\LoginController@socialLogin');
Route::get('social-callback/{provider}', 'Auth\LoginController@socialCallBack');

// Logs
Route::get('admin/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware(['auth', 'dashboard','system_log_view']);
