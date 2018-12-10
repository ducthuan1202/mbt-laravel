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
    return redirect('/home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('roles', 'RoleController');
Route::resource('cities', 'CityController');
Route::resource('companies', 'CompanyController');
Route::resource('skins', 'ProductSkinController');
Route::resource('products', 'ProductController');
Route::resource('customers', 'ProductSkinController');
Route::resource('orders', 'ProductSkinController');
Route::resource('users', 'ProductSkinController');
Route::resource('cares', 'ProductSkinController');
