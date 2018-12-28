<?php

namespace App\Http\Controllers;

use App\Debt;
use App\Helpers\Common;
use App\Order;
use App\PaymentSchedule;

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

    private function calculatorThis($date)
    {

        // prepay this week
        $order = new Order();
        $revenue = $order->getPrePay($date);

        // payment schedule this month
        $paymentSchedule = new PaymentSchedule();
        $payment = $paymentSchedule->getPaymentPaid($date);

        // debt
        $debt = new Debt();
        $debtPaid = $debt->getDebtThisTime($date);

        return [
            'revenue' => $revenue,
            'payment' => $payment,
            'debtPaid'=>$debtPaid
        ];

    }

    private function calculatorNext($date)
    {
        // debt next week
        $debt = new Debt();
        $revenue = $debt->getDebtNextTime($date);

        // payment schedule this month
        $paymentSchedule = new PaymentSchedule();
        $payment = $paymentSchedule->getPaymentNextTime($date);

        return [
            'revenue' => $revenue,
            'payment' => $payment,
        ];

    }

    public function thisWeek()
    {
        $date = Common::getDateRangeOfThisWeek();

        $data = $this->calculatorThis($date);

        return view('report/this_week', $data);
    }

    public function nextWeek()
    {
        $date = Common::getDateRangeOfNextWeek();

        $data = $this->calculatorNext($date);

        return view('report/next_week', $data);
    }

    public function thisMonth()
    {

        $date = Common::getDateRangeOfThisMonth();
        $data = $this->calculatorThis($date);

        return view('report/this_month', $data);
    }

    public function nextMonth()
    {
        $date = Common::getDateRangeOfNextMonth();
        $data = $this->calculatorNext($date);

        return view('report/next_month', $data);
    }

}
