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

$q=Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('item', 'ItemController');
Route::post('item/edit', 'ItemController@edit');
Route::resource('order', 'OrderController');
Route::get('/show', 'HomeController@game')->name('game');
Route::post('/show', 'HomeController@data')->name('game');
Route::post('/game', 'HomeController@clientorder')->name('game');
Route::get('/amount', 'AmountController@amount')->name('amount');
Route::post('/amount', 'AmountController@store')->name('amount');
Route::get('/getAmount', 'AmountController@getAmount')->name('amount');
Route::get('/order', 'OrderController@show')->name('order');
Route::get('/getOrder', 'OrderController@getOrder')->name('order');
Route::get('/registerManager', 'Auth\RegisterManager@showRegistrationForm')->name('user');
Route::post('/managerRegister', 'Auth\RegisterManager@createManager')->name('user');
Route::get('/getuser', 'Auth\RegisterManager@getUser')->name('user');
Route::put('/editUser', 'Auth\RegisterManager@editUser')->name('user');
