<?php

namespace App\Http\Controllers;

use App\Debt;
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
        $nextWeek = Common::getDateRangeOfNextWeek();
        $nextMonth = Common::getDateRangeOfNextMonth();

        // prepay this week
        $order = new Order();
        $revenuePrePayWeek = $order->getPrePay($thisWeek);
        $revenuePrePayMonth = $order->getPrePay($thisMonth);

        // payment schedule this month
        $paymentSchedule = new PaymentSchedule();
        $paymentWeek = $paymentSchedule->getPaymentPaid($thisWeek);
        $paymentMonth = $paymentSchedule->getPaymentPaid($thisMonth);
        $paymentNextWeek = $paymentSchedule->getPaymentNextTime($nextWeek);
        $paymentNextMonth = $paymentSchedule->getPaymentNextTime($nextMonth);

        // debt next week
        $debt = new Debt();
        $debtNextWeek = $debt->getDebtNextTime($nextWeek);
        $debtNextMonth= $debt->getDebtNextTime($nextMonth);

        $shared = [
            'revenuePrePayMonth' => $revenuePrePayMonth,
            'revenuePrePayWeek' => $revenuePrePayWeek,
            'paymentWeek' => $paymentWeek,
            'paymentMonth' => $paymentMonth,
            'paymentNextWeek' => $paymentNextWeek,
            'debtNextWeek' => $debtNextWeek,
            'paymentNextMonth' => $paymentNextMonth,
            'debtNextMonth' => $debtNextMonth,
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
