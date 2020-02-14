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

Route::post('/addresses', 'AddressController@addresses');
Route::post('/kladr', 'AddressController@search');

Route::post('/dealer/findDealer/', 'DealerController@findDealer');
Route::post('/customer/findCustomer/', 'CustomerController@findCustomer');

Route::get('/data/findByInn/{id}', 'DataController@findByInn');
Route::get('/data/findProductById/{id}', 'DataController@findProductById');

Route::resource('/project', 'ProjectController');
Route::resource('/managers', 'ManagerController');
Route::post('/managers/{id}/sendPassword', 'ManagerController@sendPassword');

Route::post('/project/{project}/files', 'ProjectController@saveFile');
Route::put('/project/{project}/files', 'ProjectController@updateFile');
Route::delete('/project/{project}/files/{fileProject}', 'ProjectController@deleteFile');
Route::resource('/file', 'FileController');

Route::get('/{any}', 'SinglePageController@index')->middleware('auth')->where(['any' => '.*']);

Route::resource('/product', 'ProductController');
Route::resource('/dealer', 'DealerController');
Route::resource('/customer', 'CustomerController');
Route::resource('/opponent', 'OpponentController');
Route::resource('/staff', 'StaffController');


