<?php

namespace App\Http\Controllers;

use App\Care;
use App\Customer;
use App\Debt;
use App\Helpers\Common;
use App\Order;
use App\PaymentSchedule;
use App\User;
use App\PriceQuotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    /**
     * @param $date
     * @return array
     */
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
            'date' => $date,
            'revenue' => $revenue,
            'payment' => $payment,
            'debtPaid' => $debtPaid
        ];

    }

    /**
     * @param $date
     * @return array
     */
    private function calculatorNext($date)
    {
        // debt next week
        $debt = new Debt();
        $revenue = $debt->getDebtNextTime($date);

        // payment schedule this month
        $paymentSchedule = new PaymentSchedule();
        $payment = $paymentSchedule->getPaymentNextTime($date);

        return [
            'date' => $date,
            'revenue' => $revenue,
            'payment' => $payment,
        ];

    }

    /**
     * @param string $timeRange
     * @return array
     */
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
    public function moneyPresent(Request $request)
    {
        $date = $request->get('date');
        $data = $this->calculatorThis($date);

        return view('report.money.present', $data);
    }

    public function moneyFuture(Request $request)
    {
        $date = $request->get('date');
        $data = $this->calculatorNext($date);

        return view('report.money.future', $data);
    }

    // report general
    public function overview(Request $request)
    {
        $date = $request->get('date');
        $dt = Common::extractDate($date);
        $startDate = Common::dmY2Ymd($dt[0]);
        $endDate = Common::dmY2Ymd($dt[1]);

        $uid =  $request->get('user');
        //dd($date);
        $shared = $this->byTime($date);
        $userModel = new User();
        $shared['date'] = $date;
        $shared['users'] = $userModel->getDropDownList();
        $shared['uid'] = $uid;
        //get so luong bao gia theo ngay

        $query1 = DB::table('price_quotations')
            ->select(DB::raw('count(id) as count , quotations_date as date'))
            ->whereBetween('quotations_date', [$startDate, $endDate]);
        if($uid!=0){
            $query1 = $query1->where('user_id',$uid);
        }
        $db1 = $query1->groupBy('quotations_date')->get()->toArray();
        $shared['db1'] = $db1;
        //get data ty le
        $query2 = DB::table('price_quotations')
            ->select(DB::raw('count(id) as count, status, 
            CASE
                WHEN status = 1 THEN "Thành công"
                WHEN status = 2 THEN "Đang theo"
                ELSE "Thất bại"
            END AS statusText
            '))
            ->whereBetween('quotations_date', [$startDate, $endDate]);

        if($uid!=0){
            $query2 = $query2->where('user_id',$uid);
        }

        $db2 = $query2->groupBy('status')->get()->toArray();
        $shared['db2'] = $db2;

        //get data theo user
        $query3 = DB::table('price_quotations')
            ->select(DB::raw('count(price_quotations.id) as count, users.name as name'))
            ->join('users', 'users.id', '=', 'price_quotations.user_id')
            ->whereBetween('price_quotations.quotations_date', [$startDate, $endDate]);
        if($uid!=0){
            $query3 = $query3->where('price_quotations.user_id',$uid);
        }
        $db3 = $query3->groupBy('price_quotations.user_id')->get()->toArray();
        $shared['db3'] = $db3;

        // lay du lieu so tien bao gia
        $query5 = DB::table('price_quotations')
            ->select(DB::raw('sum(total_money) as total , quotations_date as date'))
            ->whereBetween('quotations_date', [$startDate, $endDate]);
        if($uid!=0){
            $query5 = $query5->where('user_id',$uid);
        }
        $db5 = $query5->groupBy('quotations_date')->get()->toArray();
        $shared['db5'] = $db5;
        //dd($db3);

        //lay du lieu tong tien theo trang thai
        $query6 = DB::table('price_quotations')
            ->select(DB::raw('sum(total_money) as total, status, 
            CASE
                WHEN status = 1 THEN "Thành công"
                WHEN status = 2 THEN "Đang theo"
                ELSE "Thất bại"
            END AS statusText
            '))
            ->whereBetween('quotations_date', [$startDate, $endDate]);
        if($uid!=0){
            $query6 = $query6->where('user_id',$uid);
        }
        $db6 = $query6->groupBy('status')->get()->toArray();
        $shared['db6'] = $db6;
        //lay data bieu do thu 7
        $query7 = DB::table('price_quotations')
            ->select(DB::raw('sum(price_quotations.total_money) as total, users.name as name'))
            ->join('users', 'users.id', '=', 'price_quotations.user_id')
            ->whereBetween('price_quotations.quotations_date', [$startDate, $endDate]);
        if($uid!=0){
            $query7 = $query7->where('price_quotations.user_id',$uid);
        }
        $db7 = $query7->groupBy('price_quotations.user_id')->get()->toArray();
        $shared['db7'] = $db7;
       // dd($db7);
        return view('report.overview', $shared);
    }

    // TODO: customers

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * @param Request $request
     * @param $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * @param Request $request
     * @param $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * @param Request $request
     * @param $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * @param Request $request
     * @param $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
