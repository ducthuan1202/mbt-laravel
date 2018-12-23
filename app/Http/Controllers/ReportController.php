<?php

namespace App\Http\Controllers;

use App\Customer;

class ReportController extends Controller
{

    public function customers()
    {
        $shared = [
            'data' => 0,
        ];
        return view('report/customer', $shared);
    }

    public function orders()
    {
        $shared = [
            'data' => 0,
        ];
        return view('report/order', $shared);
    }

    public function quotations()
    {
        $shared = [
            'data' => 0,
        ];
        return view('report/quotation', $shared);
    }

    public function cares()
    {
        $shared = [
            'data' => 0,
        ];
        return view('report/care', $shared);
    }

    public function debts()
    {
        $shared = [
            'data' => 0,
        ];
        return view('report/debt', $shared);
    }
}
