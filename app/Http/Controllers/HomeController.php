<?php

namespace App\Http\Controllers;

use App\Care;
use App\City;
use App\Company;
use App\Customer;
use App\Order;
use App\PriceQuotation;
use App\User;
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
        $shared = [
            'customerCount' => Customer::countNumber(),
            'cityCount' => City::countNumber(),
            'orderCount' => Order::countNumber(),
            'careCount' => Care::countNumber(),
            'quotationCount' => PriceQuotation::countNumber(),
            'companyCount' => Company::countNumber(),
            'userCount' => User::countNumber(),

//            'totalMoney' => $orderModel->sumTotalMoney(),
//            'totalMoneyDebt' => $debtModel->sumTotalMoney(),
//            'eChartData' => $userModel->eChartGenerateData($eChartData),
//            'eChartCustomerData' => $customerModel->eChartGenerateData($eChartCustomerData),
        ];

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
                    'slug'=>str_slug(trim($customer->company)),
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        endforeach;
        Company::insert($list);
    }

}
