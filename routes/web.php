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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/address', 'AddressController@getAddress');

Route::get('/addform', 'FormController@add');
Route::get('/deleteform/{id}', 'FormController@delete');
Route::get('/updateform/{id}', 'FormController@update');
Route::get('/getform/{id}', 'FormController@get');
Route::get('/search', 'FormController@search');
Route::get('/allforms', 'FormController@getAll');