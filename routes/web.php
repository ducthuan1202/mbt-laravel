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

Route::middleware(['auth'])->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::resource('roles', 'RoleController');
    Route::resource('cities', 'CityController');
    Route::resource('companies', 'CompanyController');
    Route::resource('skins', 'ProductSkinController');
    Route::resource('products', 'ProductController');
    Route::resource('customers', 'CustomerController');
    Route::resource('quotations', 'PriceQuotationController');
    Route::resource('users', 'UserController');
    Route::resource('cares', 'CareController');
    Route::resource('orders', 'ProductSkinController');
});
