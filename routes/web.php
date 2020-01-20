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

Route::get('/{any}', 'SinglePageController@index')->middleware('auth')->where(['any' => '.*']);
