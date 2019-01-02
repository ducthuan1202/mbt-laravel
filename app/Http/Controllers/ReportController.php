<?php

namespace App\Http\Controllers;

use App\Care;
use App\Customer;
use App\Debt;
use App\Helpers\Common;
use App\Order;
use App\PaymentSchedule;
use App\PriceQuotation;
use App\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{

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
            'debtPaid' => $debtPaid
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

    private function byTime($timeRange = '')
    {
        $customer = new Customer();
        $care = new Care();
        $quotation = new PriceQuotation();
        $order = new Order();
        $debt = new Debt();

        $shared = [
            'customersData' => $customer->countByDate($timeRange),
            'caresDate' => $care->countByDate($timeRange),
            'quotationsData' => $quotation->countByDate($timeRange),
            'ordersData' => $order->countByDate($timeRange),
            'debtsData' => $debt->countByDate($timeRange),
            'sumOldDebt' => $debt->sumOldDebt($timeRange)
        ];

        return $shared;
    }

    // report money
    public function thisWeekMoney()
    {
        $date = Common::getDateRangeOfThisWeek();

        $data = $this->calculatorThis($date);

        return view('report.money.this_week', $data);
    }

    public function nextWeekMoney()
    {
        $date = Common::getDateRangeOfNextWeek();

        $data = $this->calculatorNext($date);

        return view('report.money.next_week', $data);
    }

    public function thisMonthMoney()
    {

        $date = Common::getDateRangeOfThisMonth();
        $data = $this->calculatorThis($date);

        return view('report.money.this_month', $data);
    }

    public function nextMonthMoney()
    {
        $date = Common::getDateRangeOfNextMonth();
        $data = $this->calculatorNext($date);

        return view('report.money.next_month', $data);
    }

    // report general
    public function overview(Request $request)
    {
        $date = $request->get('date');
        $shared = $this->byTime($date);
        $shared['date'] = $date;
        return view('report.overview', $shared);
    }

    // TODO: customers
    public function customers(Request $request)
    {
        $date = $request->get('date');
        $user = new User();
        $shared = [
            'date' => $date,
            'users' => $user->getCustomersCreated($date)
        ];

        return view('report.customers', $shared);
    }

    public function customersDetail(Request $request, $userId)
    {
        $user = new User();
        $date = $request->get('date');

        $data = $user->getCustomersCreated($date, $userId);

        $hasBuy = [];
        $noBuy = [];
        foreach ($data->customers as $item) {
            if ($item->status == Customer::BUY_STATUS) {
                $hasBuy[] = $item;
            }
            if ($item->status == Customer::NO_BUY_STATUS) {
                $noBuy[] = $item;
            }
        }

        $shared = [
            'date' => $date,
            'data' => $data,
            'customersThisWeek' => [
                'hasBuy' => $hasBuy,
                'noBuy' => $noBuy
            ]
        ];

        return view('report.customers_detail', $shared);
    }

    // TODO: cares
    public function cares(Request $request)
    {
        $date = $request->get('date');
        $user = new User();
        $shared = [
            'date' => $date,
            'users' => $user->getCaresCreated($date)
        ];

        return view('report.cares', $shared);
    }

    public function caresDetail(Request $request, $userId)
    {
        $user = new User();
        $date = $request->get('date');

        $data = $user->getCaresCreated($date, $userId);

        $shared = [
            'date' => $date,
            'data' => $data,
        ];

        return view('report.cares_detail', $shared);
    }

    // TODO: price quotations
    public function quotations(Request $request)
    {
        $date = $request->get('date');
        $user = new User();
        $shared = [
            'date' => $date,
            'users' => $user->getQuotationsCreated($date)
        ];

        return view('report.quotations', $shared);
    }

    public function quotationsDetail(Request $request, $userId)
    {
        $user = new User();
        $date = $request->get('date');

        $data = $user->getQuotationsCreated($date, $userId);

        $shared = [
            'date' => $date,
            'data' => $data,
        ];

        return view('report.quotations_detail', $shared);
    }

    // TODO: orders
    public function orders(Request $request)
    {
        $date = $request->get('date');
        $user = new User();
        $shared = [
            'date' => $date,
            'users' => $user->getOrdersCreated($date)
        ];

        return view('report.orders', $shared);
    }

    public function ordersDetail(Request $request, $userId)
    {
        $user = new User();
        $date = $request->get('date');

        $data = $user->getOrdersCreated($date, $userId);

        $shared = [
            'date' => $date,
            'data' => $data,
        ];

        return view('report.orders_detail', $shared);
    }
}
