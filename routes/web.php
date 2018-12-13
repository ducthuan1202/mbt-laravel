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

    Route::resource('cities', 'CityController')->except(['show']);
    Route::resource('companies', 'CompanyController')->except(['show']);
    Route::resource('skins', 'ProductSkinController')->except(['show']);
    Route::resource('products', 'ProductController')->except(['show']);
    Route::resource('customers', 'CustomerController')->except(['show']);
    Route::resource('quotations', 'PriceQuotationController')->except(['show']);

    Route::resource('cares', 'CareController')->except(['show']);
    Route::resource('debts', 'DebtController')->except(['show']);
    Route::resource('orders', 'ProductSkinController')->except(['show']);
    Route::resource('users', 'UserController')->except(['show']);

    Route::get('/users/change-password', 'UserController@changePassword')->name('users.change_password');
    Route::post('/users/change-password', 'UserController@updatePassword')->name('users.update_password');

    // api
    Route::get('/care-history/create', 'CareHistoryController@create');
    Route::post('/care-history/store', 'CareHistoryController@store');
    Route::get('/care-history', 'CareHistoryController@index');
});
