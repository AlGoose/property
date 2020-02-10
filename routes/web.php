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

use Illuminate\Support\Facades\Password;

Auth::routes();
Route::get('/test', function () {
    $user = \App\User::find(1);
    $broker = Password::broker();
    $token = $broker->getToken($user);

    \Mail::to($user)->send((new \App\Mail\NewUser($user,$token)));


});
Route::post('/addresses', 'AddressController@addresses');
Route::post('/kladr', 'AddressController@search');

Route::post('/dealer/findDealer/', 'DealerController@findDealer');
Route::post('/customer/findCustomer/', 'CustomerController@findCustomer');

Route::get('/data/findByInn/{id}', 'DataController@findByInn');
Route::get('/data/findProductById/{id}', 'DataController@findProductById');

Route::resource('/project', 'ProjectController');
Route::resource('/managers', 'ManagerController');

Route::get('/{any}', 'SinglePageController@index')->middleware('auth')->where(['any' => '.*']);

Route::resource('/product', 'ProductController');
Route::resource('/dealer', 'DealerController');
Route::resource('/customer', 'CustomerController');
Route::resource('/opponent', 'OpponentController');
Route::resource('/staff', 'StaffController');


