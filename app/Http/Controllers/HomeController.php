<?php

namespace App\Http\Controllers;

use App\Care;
use App\City;
use App\Customer;
use App\Debt;
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

        $orderModel = new Order();
        $customerModel = new Customer();
        $eChartCustomerData = $customerModel->countCustomerByStatus();

        $userModel = new User();
        $eChartData = $userModel->countCustomerByUser();

        $debtModel = new Debt();
        $shared = [
            'customerCount' => Customer::countNumber(),
            'userCount' => User::countNumber(),
            'cityCount' => City::countNumber(),
            'orderCount' => Order::countNumber(),
            'careCount' => Care::countNumber(),
            'totalMoney' => $orderModel->sumTotalMoney(),
            'totalMoneyDebt' => $debtModel->sumTotalMoney(),
            'eChartData' => $userModel->eChartGenerateData($eChartData),
            'eChartCustomerData' => $customerModel->eChartGenerateData($eChartCustomerData),
        ];

        return view('pages/dashboard', $shared);
    }

    public function convertData(){
        $this->convertQuotations();
    }

    public function updateCode(){

        $customers = Customer::select('id', 'code')->get();
        foreach ($customers as $customer){
            $customer->code = $customer->generateUniqueCode();
            $customer->save();
        }

    }

    public function convertCustomer(){
        $customers = DB::connection('mysql01')->select("select * from bao_gia where 1");

        foreach ($customers as $customer){
            $model = new Customer();
            $model->code = 'MBT-KH00000';
            $model->user_id = 1;
            $model->city_id = 1;
            $model->company = $customer->ten_cong_ty;
            $model->address = $customer->dia_chi;
            $model->name = $customer->ten_kh;
            $model->position = $customer->chuc_vu;
            $model->mobile = $customer->sdt;
            $model->average_sale = $customer->doanh_so_tb;
            $model->status = $customer->status == Customer::BUY_STATUS ? Customer::BUY_STATUS : Customer::NO_BUY_STATUS;
            $model->save();
        }
    }

    public function convertQuotations(){
        $data = DB::connection('mysql01')->select("select * from bao_gia where 1");

        foreach ($data as $item){
            $model = new PriceQuotation();
            $model->user_id = 1;
            $model->customer_id = 1;
            $model->amount = $item->so_luong;
            $model->price = $item->don_gia;
            $model->total_money = $item->tong_don_hang;
            $model->quotations_date = $item->ngay_bao_gia;
            $model->power = $item->cong_suat;
            $model->voltage_output = $item->dien_ap_ra;
            $model->voltage_input = $item->dien_ap_vao;
            $model->standard_output = $item->tieu_chuan;
            $model->guarantee = $item->bao_hanh;
            $model->product_skin = 0;
            $model->product_type = 0;
            $model->setup_at = $item->lap_tai;
            $model->delivery_at = $item->giao_hang_tai;
            $model->order_status = $item->status == PriceQuotation::UNSIGNED_ORDER_STATUS ? PriceQuotation::UNSIGNED_ORDER_STATUS : PriceQuotation::SIGNED_ORDER_STATUS;
            $model->note = $item->note;
            $model->reason = '';
            $model->status = 0;
            $model->save();
        }
    }
}
