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
Auth::routes();

// Route::get('/address', 'AddressController@getAddress');
Route::get('/addresses', 'ProjectController@addresses');

Route::resource('/project', 'ProjectController');
// Route::get('/test', 'ProjectController@savestaff');
Route::get('/dealer/findByInn/{id}', 'DealerController@findByInn');
Route::get('/customer/findByInn/{id}', 'CustomerController@findByInn');
Route::get('/product/findById/{id}', 'ProductController@findById');
Route::get('/dealer/getStaff/{id}', 'DealerController@getStaff');
Route::get('/customer/getStaff/{id}', 'CustomerController@getStaff');

Route::get('/{any}', 'SinglePageController@index')->middleware('auth')->where(['any' => '.*']);

// Route::post('/addProduct', 'ProjectController@addProduct');
// Route::post('/addDealer', 'ProjectController@addDealer');
// Route::post('/addCustomer', 'ProjectController@addCustomer');
// Route::post('/addOpponent', 'ProjectController@addOpponent');

Route::resource('/product', 'ProductController');
Route::resource('/dealer', 'DealerController');

Route::resource('/customer', 'CustomerController');
Route::resource('/opponent', 'OpponentController');
Route::resource('/staff', 'StaffController');

