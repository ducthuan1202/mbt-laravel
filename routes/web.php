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

//Auth::routes();

Route::namespace('Auth')->group(function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout')->middleware('auth');
});

Route::middleware(['auth'])->group(function () {


    Route::get('/', 'HomeController@index')->name('home');

    Route::resource('cities', 'CityController')->except(['show']);
    Route::resource('products', 'ProductController')->except(['show']);
    Route::resource('customers', 'CustomerController')->except(['show']);
    Route::resource('quotations', 'PriceQuotationController')->except(['show']);
    Route::resource('cares', 'CareController')->except(['show']);
    Route::resource('orders', 'OrderController')->except(['show']);
    Route::resource('users', 'UserController')->except(['show']);
    Route::resource('debts', 'DebtController')->except(['show']);

    // change password
    Route::get('/users/change-password', 'UserController@changePassword')->name('users.change_password');
    Route::post('/users/change-password', 'UserController@updatePassword')->name('users.update_password');

    // api
    Route::get('/customers/by-city', 'CustomerController@getByCity');
    Route::get('/quotations/detail', 'PriceQuotationController@detail');
    Route::get('/orders/detail', 'OrderController@detail');
});