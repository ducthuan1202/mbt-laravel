<?php

namespace App\Http\Controllers;

use App\City;
use App\Company;
use App\Customer;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $shared = [
            'customerCount' => Customer::countNumber(),
            'userCount' => User::countNumber(),
            'cityCount' => City::countNumber(),
            'companyCount' => 0,
        ];

        return view('pages/dashboard', $shared);
    }
}
