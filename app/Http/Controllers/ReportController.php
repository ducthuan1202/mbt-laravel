<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use App\PaymentSchedule;
use Illuminate\Http\Request;
use App\Order;

class ReportController extends Controller
{

    public function index()
    {

        $thisWeek = Common::getDateRangeOfThisWeek();
        $thisMonth = Common::getDateRangeOfThisMonth();

        // order pre pay
        $order = new Order();
        $revenuePrePayWeek = $order->getPrePay($thisWeek);
        $revenuePrePayMonth = $order->getPrePay($thisMonth);

        // payment schedule
        $paymentSchedule = new PaymentSchedule();
        $paymentWeek = $paymentSchedule->getPayment($thisWeek);
        $paymentMonth = $paymentSchedule->getPayment($thisMonth);

        $shared = [
            'revenuePrePayMonth' => $revenuePrePayMonth,
            'revenuePrePayWeek' => $revenuePrePayWeek,
            'paymentWeek' => $paymentWeek,
            'paymentMonth' => $paymentMonth,
        ];

        return view('report/index', $shared);
    }

    public function revenue(Request $request)
    {
        $order = new Order();

        $thisWeek = $request->get('thisWeek');
        $thisMonth = $request->get('thisMonth');

        // order pre pay
        $revenuePrePayWeek = $order->getPrePay($thisWeek);
        $revenuePrePayMonth = $order->getPrePay($thisMonth);

        // payment schedule
        $paymentSchedule = new PaymentSchedule();
        $paymentWeek = $paymentSchedule->getPrePay($thisWeek);
        $paymentMonth = $paymentSchedule->getPrePay($thisMonth);

        $shared = [
            'revenuePrePayMonth' => $revenuePrePayMonth,
            'revenuePrePayWeek' => $revenuePrePayWeek,
            'paymentWeek' => $paymentWeek,
            'paymentMonth' => $paymentMonth,
        ];
        $output = [
            'success' => true,
            'message' => view('report/revenue', $shared)->render()
        ];
        return response()->json($output);

    }
}
