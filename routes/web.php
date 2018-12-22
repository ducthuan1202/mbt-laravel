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
    Route::get('/convert-data', 'HomeController@convertData');
//    Route::get('/update-code', 'HomeController@updateCode');

    // change password
    Route::get('/users/change-password', 'UserController@changePassword')->name('users.change_password');
    Route::post('/users/change-password', 'UserController@updatePassword')->name('users.update_password');

    // api
    Route::get('/customers/by-city', 'CustomerController@getByCity');
    Route::get('/quotations/detail', 'PriceQuotationController@detail');
    Route::get('/orders/detail', 'OrderController@detail');
    Route::get('/orders/by-customer', 'OrderController@getByCustomer');

    // resource
    Route::resource('cities', 'CityController')->except(['show']);
    Route::resource('customers', 'CustomerController');
    Route::resource('quotations', 'PriceQuotationController')->except(['show']);
    Route::resource('cares', 'CareController')->except(['show']);
    Route::resource('orders', 'OrderController');
    Route::resource('users', 'UserController')->except(['show']);
    Route::resource('debts', 'DebtController')->except(['show']);
    Route::get('payment-schedules/{orderId}', 'PaymentScheduleController@index')->name('payment-schedules.index');
    Route::post('payment-schedules/{orderId}', 'PaymentScheduleController@store')->name('store');

});

