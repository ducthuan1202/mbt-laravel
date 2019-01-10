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
        /** @var $customers Customer[] */

        // step 1
//        $this->createCompanyFromCustomer();

        // step 2
//        $this->setCompanyIdToCustomer();

        // step 3
        $this->step3();
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

    /**
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcel()
    {
        // doc: https://phpspreadsheet.readthedocs.io/en/stable/topics/recipes/

        $fontBold = ['font' => ['bold' => true]];

        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);

        $sheet->getRowDimension('1')->setRowHeight(50);
        $sheet->getRowDimension('2')->setRowHeight(50);

        $sheet->getRowDimension('6')->setRowHeight(50);
        $sheet->getRowDimension('8')->setRowHeight(50);

        $sheet->mergeCells('A1:G1')
            ->setCellValue('A1', 'CÔNG TY CP CHẾ TẠO BIẾN THẾ ĐIỆN LỰC HÀ NỘI');

        $sheet->mergeCells('A2:G2')
            ->setCellValue('A2', 'Địa chỉ: Điểm Công nghiệp Sông Cùng,xã Đồng Tháp, huyện Đan Phượng, Hà Nội');

        $sheet->mergeCells('A3:G3')
            ->setCellValue('A3', 'ĐT: 0466.800.815 - Fax: 043 765 3511');

        $sheet->mergeCells('A4:G4')
            ->setCellValue('A4', 'Tài khoản số: 21510000513646 Ngân hàng đầu tư và phát triển Cầu Giấy');

        $sheet->mergeCells('A6:G6')
            ->setCellValue('A6', 'BÁO GIÁ THIẾT BỊ ĐIỆN');

        $sheet->mergeCells('A8:G8')
            ->setCellValue('A8', 'KÍNH GỬI: QUÝ KHÁCH HÀNG');

        $sheet->mergeCells('A9:C9')
            ->setCellValue('A9', 'Người nhận: Nguyễn Đức Thuận');
        $sheet->mergeCells('D9:G9')
            ->setCellValue('D9', 'Người gửi: Công Ty MBT');

        $sheet->mergeCells('A10:C10')
            ->setCellValue('A10', 'Địa chỉ: Đức Bác - Sông Lô - Vĩnh Phúc');
        $sheet->mergeCells('D10:G10')
            ->setCellValue('D10', 'Người gửi: abc');

        $sheet->mergeCells('A11:C11')
            ->setCellValue('A11', 'Điện thoại/Fax: 0974600428');
        $sheet->mergeCells('D11:G11')
            ->setCellValue('D11', 'Bộ phận: Phòng Kinh doanh');

        $sheet->mergeCells('A12:G12')
            ->setCellValue('A12', 'Công ty cổ phần chế tạo biến thế điện lực Hà Nội xin gửi tới Quý khách hàng lời chào trân trọng và hợp tác. Theo đề nghị của của Quý khách hàng và khả năng đáp ứng của chúng tôi, Công ty cổ phần chế tạo biến thế điện lực Hà Nội, xin gửi báo giá vật tư - thiết bị điện như sau:');

        // apply style
        $sheet->getStyle('A1:A8')->applyFromArray($fontBold);

        // bảng chi tiết báo giá
        $sheet->setCellValue('A13', 'STT');
        $sheet->setCellValue('B13', 'Tên hàng hóa, quy cách');
        $sheet->setCellValue('C13', 'Đơn vị');
        $sheet->setCellValue('D13', 'Số lượng');
        $sheet->setCellValue('E13', 'Đơn giá');
        $sheet->setCellValue('F13', 'Thành tiền');
        $sheet->setCellValue('G13', 'Ghi chú');

        $sheet->getStyle('A13:G13')->applyFromArray($fontBold);

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,]
            ]
        ];
        $sheet->getStyle('A13:G14')->applyFromArray($styleArray);
        // data
        $sheet->setCellValue('A14', '1');
        $sheet->getCell('B14')->setValue("MBA 250kVA-35/0,4kV \n Máy kiểu hở, sứ thường");
        $sheet->getStyle('B14')->getAlignment()->setWrapText(true);

        $sheet->setCellValue('C14', 'máy');
        $sheet->setCellValue('D14', '01');
        $sheet->setCellValue('E14', '130000000')
            ->getStyle('E14')->getNumberFormat()
            ->setFormatCode('#,##0');

        $sheet->setCellValue('F14', 'Thành tiền');
        $sheet->setCellValue('G14', 'TCVN 8525-2015');

        // money
        $sheet->mergeCells('A15:E15')
            ->setCellValue('A15', 'Tổng tiền trước thuế');
        $sheet->mergeCells('D15:G15')
            ->setCellValue('D15', '130000000')
            ->getStyle('D15')->getNumberFormat()
            ->setFormatCode('#,##0');

        $sheet->mergeCells('A16:E16')
            ->setCellValue('A16', 'Thuế GTGT 10%');
        $sheet->mergeCells('D16:G16')
            ->setCellValue('D16', '130000000');

        $sheet->mergeCells('A17:E17')
            ->setCellValue('A17', 'Tổng cộng sau thuế');
        $sheet->mergeCells('D17:G17')
            ->setCellValue('D17', '143000000');

        $sheet->mergeCells('A17:G17')
            ->setCellValue('A17', 'Giá bán trên đã bao gồm thuế GTGT 10%');

        $sheet->getStyle('A15:G17')->applyFromArray($fontBold);

        // section 3

        $sheet->mergeCells('A18:G18')
            ->setCellValue('A18', 'Điều kiện thanh toán :Tạm ứng 30% giá trị khi chính thức đặt hàng. Thanh toán giá trị còn lại trước khi nhận hàng. (mặc định)');


        $sheet->mergeCells('A19:G19')
            ->setCellValue('A19', 'Thời gian giao hàng: Giao hàng sau 15-20 ngày kể từ ngày nhận được tiền tạm ứng.');

        $sheet->mergeCells('A20:G20')
            ->setCellValue('A18', 'Điều kiện giao hàng: Giao hàng  tại kho Hải Phòng (ô nơi giao hàng)');

        $sheet->mergeCells('A21:G21')
            ->setCellValue('A21', 'Thời gian bảo hành: 12 th¸ng.');

        $sheet->mergeCells('A22:G22')
            ->setCellValue('A22', 'Báo giá này có hiệu lực trong vòng 30 ngày kể từ ngày báo giá.');

        $sheet->mergeCells('A23:G23')
            ->setCellValue('A23', 'Kính đề nghị quý Khách hàng xác nhận việc nhận được báo giá này và vui lòng liên hệ với chúng tôi nếu cần thêm thông tin. Rất mong nhận được sự hợp tác tốt đẹp của quý Khách hàng.');

        $sheet->mergeCells('A24:C24')
            ->setCellValue('A24', 'XÁC NHẬN ĐẶT HÀNG');
        $sheet->mergeCells('D24:G24')
            ->setCellValue('D24', 'CÔNG TY CP CHẾ TẠO BIẾN THẾ ĐIỆN LỰC HÀ NỘI');

        $sheet->getStyle('A24:G24')->applyFromArray($fontBold);

        $writer = new Xlsx($spreadSheet);

        $fileName = 'excel/template/quotation.xlsx';
        $writer->save(public_path($fileName));
        return public_path($fileName);
    }

    public function exportExcel1()
    {

        $templatePath = public_path('excel/template');
        $originFile = sprintf('%s/%s', $templatePath, 'bao-gia.xlsx');
        $tempFile = sprintf('%s/%s', $templatePath, 'bao-gia-01.xlsx');

        $spreadsheet = IOFactory::load($originFile);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'New Value');

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);


//        $spreadSheet = new Spreadsheet();
//        $sheet = $spreadSheet->getActiveSheet();
//        $sheet->setCellValue('A1', 'Hello');
//        $sheet->setCellValue('B1', 'worldsss s');
//        $writer = new Xlsx($spreadSheet);
//
//        $fileName = 'excel/export/hello.xlsx';
//        $writer->save(public_path($fileName));
//        return 'done';
    }
}
