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

    Route::get('/users/change-password', 'UserController@changePassword')->name('users.change_password');
    Route::get('/users/{id}/login-as', 'UserController@loginAs')->name('users.login_as');
    Route::post('/users/change-password', 'UserController@updatePassword')->name('users.update_password');
    Route::get('payment-schedules/{orderId}', 'PaymentScheduleController@index')->name('payment-schedules.index');
    Route::post('payment-schedules/{orderId}', 'PaymentScheduleController@store')->name('payment-schedules.store');
    Route::post('payment-schedules/{debtId}/debt', 'PaymentScheduleController@storeDebt')->name('payment-schedules.store_debt');
    Route::get('/debts/old', 'DebtController@oldDebt')->name('debts.list_old');
    Route::get('/debts/new', 'DebtController@newDebt')->name('debts.list_new');
    Route::get('/orders/detail-by-code/{code}', 'OrderController@detailByCode')->name('orders.detail_by_code');

    // api
    Route::get('/customers/by-city', 'CustomerController@getByCity');
    Route::get('/customers/cities-by-user', 'CustomerController@getCitiesByUser');
    Route::get('/quotations/detail', 'PriceQuotationController@detail');
    Route::get('/orders/detail', 'OrderController@detail');
    Route::get('/orders/by-customer', 'OrderController@getByCustomer');
    Route::get('payment-schedules/ajax/{orderId}', 'PaymentScheduleController@openForm')->name('payment-schedules.open_form');
    Route::put('payment-schedules/ajax/{orderId}', 'PaymentScheduleController@saveForm')->name('payment-schedules.save_form');
    Route::delete('payment-schedules/ajax/{orderId}', 'PaymentScheduleController@deleteForm')->name('payment-schedules.delete_form');

    //report
    Route::get('/report', 'ReportController@overview')->name('report.overview');
    Route::get('/report/customers', 'ReportController@customers')->name('report.customers');
    Route::get('/report/{userId}/customers', 'ReportController@customersDetail')->name('report.customers_detail');
    Route::get('/report/cares', 'ReportController@cares')->name('report.cares');
    Route::get('/report/{userId}/cares', 'ReportController@caresDetail')->name('report.cares_detail');
    Route::get('/report/quotations', 'ReportController@quotations')->name('report.quotations');
    Route::get('/report/{userId}/quotations', 'ReportController@quotationsDetail')->name('report.quotations_detail');
    Route::get('/report/orders', 'ReportController@orders')->name('report.orders');
    Route::get('/report/{userId}/orders', 'ReportController@ordersDetail')->name('report.orders_detail');
    Route::get('/report/money/present', 'ReportController@moneyPresent')->name('report.money_present');
    Route::get('/report/money/future', 'ReportController@moneyFuture')->name('report.money_future');

    // resource
    Route::resource('cities', 'CityController')->except(['show']);
    Route::resource('companies', 'CompanyController')->except(['show']);
    Route::resource('customers', 'CustomerController');
    Route::resource('quotations', 'PriceQuotationController');
    Route::get('quotations/{id}/print', 'PriceQuotationController@print')->name('quotations.print');
    Route::resource('cares', 'CareController')->except(['show']);
    Route::resource('orders', 'OrderController');
    Route::resource('users', 'UserController')->except(['show']);
    Route::resource('debts', 'DebtController');
});
