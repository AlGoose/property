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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/{any}', 'SinglePageController@index')->where('any', '.*');

Auth::routes();


// Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/address', 'AddressController@getAddress');
// Route::get('/addresses', 'ProjectController@addresses');

// Route::get('/addform', 'FormController@add');
// Route::get('/deleteform/{id}', 'FormController@delete');
// Route::get('/updateform/{id}', 'FormController@update');
// Route::get('/getform/{id}', 'FormController@get');
// Route::get('/search', 'FormController@search');
// Route::get('/allforms', 'FormController@getAll');

// Route::resource('project', 'ProjectController');

https://laravelnews.ru/sozdaem-spa-s-pomoshchyu-vue-i-laravel-chast-1
https://vivasart.com/lab/bystryy-start-s-laravel-55-vuejs-prostoy-crud
https://medium.com/@weehong/laravel-5-7-vue-vue-router-spa-5e07fd591981