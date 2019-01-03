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
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $customers = Customer::select('id', 'code')->get();
        foreach ($customers as $customer) {
            $customer->code = $customer->generateUniqueCode();
            $customer->save();
        }
    }

    /**
     * convertQuotations
     */
    public function convertQuotations()
    {
        return '';
        $data = DB::connection('mysql01')->select("select * from bao_gia where 1");

        foreach ($data as $item) {
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

    /**
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function importCustomer()
    {
        return '';
        $cities = City::select('id', 'name')->get()->toArray();
        $users = User::select('id', 'name')->get()->toArray();

        $file = public_path('customers.xlsx');

        if (file_exists($file)) {
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadSheet = $reader->load($file);
            $sheetData = $spreadSheet->getActiveSheet()->toArray();

            $data = [];
            foreach ($sheetData as $index => $sheet) {

                if (!empty($sheet[2]) && !empty($sheet[4])) {
                    $city = collect($cities)->firstWhere('name', $sheet[7]);
                    $user = collect($users)->firstWhere('name', $sheet[1]);

                    $data[] = [
                        'code' => 'MBT-KH00000',
                        'user_id' => $user['id'],
                        'city_id' => $city['id'],
                        'name' => $sheet[2],
                        'position' => empty($sheet[3]) ? 'Nhân Viên' : $sheet[3],
                        'mobile' => $sheet[4],
                        'company' => $sheet[5],
                        'address' => $sheet[6],
                        'status' => ((int)$sheet[8] == 1 || (string)$sheet[8] == '1') ? 1 : 2,
                        'created_at' => date('Y-m-d'),
                        'updated_at' => date('Y-m-d'),
                    ];
                }
            }
            DB::table('customers')->insert($data);
            return sprintf('done');
        } else {
            return sprintf('file không tồn tại.');
        }
    }

    /**
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcel()
    {
        return '';
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello');
        $sheet->setCellValue('B1', 'worldsss s');
        $writer = new Xlsx($spreadSheet);

        $fileName = 'excel/export/hello.xlsx';
        $writer->save(public_path($fileName));
        return 'done';
    }
}
