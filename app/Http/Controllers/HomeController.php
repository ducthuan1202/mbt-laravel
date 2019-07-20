<?php

namespace App\Http\Controllers;

use App\Care;
use App\City;
use App\Company;
use App\Customer;
use App\Order;
use App\PriceQuotation;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $orderModel = new Order();
//        $customerModel = new Customer();
//        $eChartCustomerData = $customerModel->countCustomerByStatus();


//        $userModel = new User();
//        $eChartData = $userModel->countCustomerByUser();
//        $debtModel = new Debt();


        $today = date("Y-m-d");
        $t1 = Carbon::now()->subDays(1)->startOfDay()->format('Y-m-d');
        $t2 = Carbon::now()->subDays(2)->startOfDay()->format('Y-m-d');
        $t3 = Carbon::now()->subDays(3)->startOfDay()->format('Y-m-d');
        $t4 = Carbon::now()->subDays(4)->startOfDay()->format('Y-m-d');
        $t5 = Carbon::now()->subDays(5)->startOfDay()->format('Y-m-d');
        $t6 = Carbon::now()->subDays(6)->startOfDay()->format('Y-m-d');
        $today_data = PriceQuotation::getDataByDate($today);
        $t1_data = PriceQuotation::getDataByDate($t1);
        $t2_data = PriceQuotation::getDataByDate($t2);
        $t3_data = PriceQuotation::getDataByDate($t3);
        $t4_data = PriceQuotation::getDataByDate($t4);
        $t5_data = PriceQuotation::getDataByDate($t5);
        $t6_data = PriceQuotation::getDataByDate($t6);


        $shared = [
            'customerCount' => Customer::countNumber(),
            'cityCount' => City::countNumber(),
            'orderCount' => Order::countNumber(),
            'careCount' => Care::countNumber(),
            'quotationCount' => PriceQuotation::countNumber(),
            'companyCount' => Company::countNumber(),
            'userCount' => User::countNumber(),
            'today' => $today_data,
            't1' => $t1_data,
            't2' => $t2_data,
            't3' => $t3_data,
            't4' => $t4_data,
            't5' => $t5_data,
            't6' => $t6_data,
        ];

        //dd($t6_data);

        return view('pages/dashboard', $shared);
    }

    /**
     * updateCode
     */
    public function updateCode()
    {
        /** @var $customers Customer[] */

        // step 1
//        $this->createCompanyFromCustomer();

        // step 2
//        $this->setCompanyIdToCustomer();

        // step 3
//        $this->step3();
        return 'done';
    }

    private function step3()
    {
        $customers = Customer::whereNotNull('company')
            ->where('company_id', '0')
            ->get();
        $companies = Company::get();

        foreach ($customers as $customer):
            $company = array_first($companies, function ($item) use ($customer) {
                return $item->slug == str_slug(trim($customer->company));
            });
            if ($company) {
                $customer->company_id = $company->id;
            } else {
                $customer->company_id = 0;
            }

            $customer->save();
        endforeach;
    }

    private function setCompanyIdToCustomer()
    {
        $customers = Customer::get();
        $companies = Company::get();

        foreach ($customers as $customer):
            $company = array_first($companies, function ($item) use ($customer) {
                return $item->slug == str_slug(trim($customer->company));
            });

            if ($company) {
                $customer->company_id = $company->id;
            } else {
                $customer->company_id = 0;
            }

            $customer->save();
        endforeach;
    }

    private function createCompanyFromCustomer()
    {
        $customers = DB::table('customers')
            ->selectRaw(DB::raw("DISTINCT(company)"))
            ->get();
        $list = [];
        foreach ($customers as $customer):
            if (!empty($customer->company)) {
                $list[] = [
                    'name' => trim($customer->company),
                    'slug' => str_slug(trim($customer->company)),
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        endforeach;
        Company::insert($list);
    }

}
