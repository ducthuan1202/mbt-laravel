<?php

namespace App\Http\Controllers;

use App\Care;
use App\City;
use App\Customer;
use App\Order;
use App\User;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $orderModel = new Order();
        $customerModel = new Customer();
        $eChartCustomerData = $customerModel->countCustomerByStatus();

        $userModel = new User();
        $eChartData = $userModel->countCustomerByUser();
        $shared = [
            'customerCount' => Customer::countNumber(),
            'userCount' => User::countNumber(),
            'cityCount' => City::countNumber(),
            'orderCount' => Order::countNumber(),
            'careCount' => Care::countNumber(),
            'totalMoney' => $orderModel->sumTotalMoney(),
            'eChartData' => $userModel->eChartGenerateData($eChartData),
            'eChartCustomerData' => $customerModel->eChartGenerateData($eChartCustomerData),
        ];

        return view('pages/dashboard', $shared);
    }
}
