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

// Route::get('/import/customer', 'HomeController@importCustomer');
// Route::get('/convert-data', 'HomeController@convertData');
// Route::get('/update-code', 'HomeController@updateCode');

Route::namespace('Auth')->group(function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout')->middleware('auth');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/', 'HomeController@index')->name('home');

    // change password
    Route::get('/users/change-password', 'UserController@changePassword')->name('users.change_password');
    Route::post('/users/change-password', 'UserController@updatePassword')->name('users.update_password');
    Route::get('payment-schedules/{orderId}', 'PaymentScheduleController@index')->name('payment-schedules.index');
    Route::post('payment-schedules/{orderId}', 'PaymentScheduleController@store')->name('payment-schedules.store');
    Route::get('debts/old', 'DebtController@oldDebt')->name('debts.list_old');
    Route::get('debts/new', 'DebtController@newDebt')->name('debts.list_new');
    Route::get('/orders/shipped', 'OrderController@shipped')->name('orders.shipped');
    Route::get('/orders/no-shipped', 'OrderController@noShipped')->name('orders.no_shipped');
    Route::get('/orders/cancel', 'OrderController@cancel')->name('orders.cancel');
    // api
    Route::get('/customers/by-city', 'CustomerController@getByCity');
    Route::get('/customers/cities-by-user', 'CustomerController@getCitiesByUser');
    Route::get('/quotations/detail', 'PriceQuotationController@detail');
    Route::get('/orders/detail', 'OrderController@detail');
    Route::get('/orders/by-customer', 'OrderController@getByCustomer');
    Route::get('payment-schedules/ajax/{orderId}', 'PaymentScheduleController@openForm')->name('payment-schedules.open_form');
    Route::put('payment-schedules/ajax/{orderId}', 'PaymentScheduleController@saveForm')->name('payment-schedules.save_form');

    //report
    Route::get('/report/customers', 'ReportController@customers')->name('report.customers');
    Route::get('/report/orders', 'ReportController@orders')->name('report.orders');
    Route::get('/report/quotations', 'ReportController@quotations')->name('report.quotations');
    Route::get('/report/cares', 'ReportController@cares')->name('report.cares');
    Route::get('/report/debts', 'ReportController@debts')->name('report.debts');

    // resource
    Route::resource('cities', 'CityController')->except(['show']);
    Route::resource('customers', 'CustomerController');
    Route::resource('quotations', 'PriceQuotationController');
    Route::resource('cares', 'CareController')->except(['show']);
    Route::resource('orders', 'OrderController');
    Route::resource('users', 'UserController')->except(['show']);
    Route::resource('debts', 'DebtController');
});
