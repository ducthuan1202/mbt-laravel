<?php

namespace App\Http\Controllers;

use App\City;
use App\Customer;
use App\Helpers\Messages;
use App\PriceQuotation;
use App\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class PriceQuotationController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        $searchParams = [
            'customer' => null,
            'user' => null,
            'city' => null,
            'keyword' => null,
            'date' => null,
            'status' => null,
            'power' => null,
            'setup_at' => null,
        ];
        $searchParams = array_merge($searchParams, $request->all());

        // get relation
        $cityModel = new City();
        $userModel = new User();
        $customerModel = new Customer();
        $model = new PriceQuotation();

        $shared = [
            'model' => $model,
            'data' => $model->search($searchParams),
            'counter' => $model->countByStatus($searchParams),
            'sumMoney' => $model->countMoney($searchParams),
            'searchParams' => $searchParams,
            'users' => $userModel->getDropDownList(true),
            'cities' => $cityModel->getDropDownList(true),
            'customers' => $customerModel->getDropDownList(true),
        ];

        return view('quotation.index', $shared);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userModel = new User();
        $cityModel = new City();
        $customerModel = new Customer();
        $model = new PriceQuotation();
        $model->quotations_date = date('Y-m-d');
        $model->amount = 1;
        $model->total_money = 0;
        $model->terms_of_payment = 'Thanh toán giá trị còn lại trước khi nhận hàng';
        $model->expired = 20;
        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
            'cities' => $cityModel->getDropDownList(),
            'customers' => $customerModel->getDropDownList(),
        ];
        return view('quotation.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $model = new PriceQuotation();
        $this->validate($request, $model->validateRules, $model->validateMessage);

        $model->fill($request->all());
        $model->checkBeforeSave();
        if ($model->save()) {
            $model->code = str_slug($model->user->name) . '-' . $model->id;
            $model->save();
        }
        return redirect()
            ->route('quotations.index')
            ->with('success', Messages::INSERT_SUCCESS);
    }

    public function show($id)
    {
        $model = $this->finById($id);
        $shared = [
            "model" => $model,
        ];
        return view('quotation.detail', $shared);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userModel = new User();
        $cityModel = new City();
        $customerModel = new Customer();

        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
            'cities' => $cityModel->getDropDownList(),
            'customers' => $customerModel->getDropDownList(),
        ];
        return view('quotation.edit', $shared);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $model = $this->finById($id);
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->checkBeforeSave();
        $model->save();
        return redirect()
            ->route('quotations.index')
            ->with('success', Messages::UPDATE_SUCCESS);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        # find model and delete
        $model = $this->finById($id);

        if ($model->delete()) {
            $output = [
                'success' => true,
                'message' => Messages::DELETE_SUCCESS
            ];
        } else {
            $output = [
                'success' => false,
                'message' => Messages::DELETE_ERROR
            ];
        }
        return response()->json($output);
    }

    /**
     * @param $id
     * @return PriceQuotation
     */
    protected function finById($id)
    {
        return PriceQuotation::findOrFail($id);
    }

    public function detail(Request $request)
    {
        $id = $request->get('id');
        $model = $this->finById($id);

        $shared = [
            'model' => $model,
        ];
        $output = [
            'success' => true,
            'message' => view('quotation.ajax.detail', $shared)->render()
        ];
        return response()->json($output);
    }

    public function print($id)
    {
        // doc: https://phpspreadsheet.readthedocs.io/en/stable/topics/recipes/

        $title = [
            'header' => [
                'row1' => 'CÔNG TY CP CHẾ TẠO BIẾN THẾ ĐIỆN LỰC HÀ NỘI',
                'row2' => 'Địa chỉ: Điểm Công nghiệp Sông Cùng, xã Đồng Tháp, huyện Đan Phượng, Hà Nội',
                'row3' => 'ĐT: 0466.800.815 - Fax: 043 765 3511',
                'row4' => 'Tài khoản số: 21510000513646 Ngân hàng đầu tư và phát triển Cầu Giấy',
                'row5' => 'BÁO GIÁ THIẾT BỊ ĐIỆN',
                'row6' => 'KÍNH GỬI: QUÝ KHÁCH HÀNG',
            ],
            'desc' => 'Công ty cổ phần chế tạo biến thế điện lực Hà Nội xin gửi tới Quý khách hàng lời chào trân trọng và hợp tác. Theo đề nghị của của Quý khách hàng và khả năng đáp ứng của chúng tôi, Công ty cổ phần chế tạo biến thế điện lực Hà Nội, xin gửi báo giá vật tư - thiết bị điện như sau:',
            'footer' => [
                'row1' => 'Kính đề nghị quý Khách hàng xác nhận việc nhận được báo giá này và vui lòng liên hệ với chúng tôi nếu cần thêm thông tin. Rất mong nhận được sự hợp tác tốt đẹp của quý Khách hàng.',
                'confirm' => 'XÁC NHẬN ĐẶT HÀNG',
                'signature' => 'CÔNG TY CP CHẾ TẠO BIẾN THẾ ĐIỆN LỰC HÀ NỘI',
            ],
        ];

        $model = $this->finById($id);

        // style list
        $fontBold = ['font' => ['bold' => true]];
        $fontItalic = ['font' => ['italic' => true]];
        $fontFamily = 'Times New Roman';
        $fontBig = 18;
        $fontMedium = 16;
        $fontSmall = 14;
        $borderThin = ['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,]]];
        $horizontalCenter = ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,]];
        $horizontalRight = ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,]];
        $verticalCenter = ['alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,]];

        $verAndHorCenter = ['alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ]
        ];

        // khởi tạo spreadSheet
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();


        // set with column
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);

        // set text
        $sheet->mergeCells('A1:G1')->setCellValue('A1', $title['header']['row1']);
        $sheet->getStyle('A1')->getFont()->setSize($fontSmall);
        $sheet->mergeCells('A2:G2')->setCellValue('A2', $title['header']['row2']);
        $sheet->mergeCells('A3:G3')->setCellValue('A3', $title['header']['row3']);
        $sheet->mergeCells('A4:G4')->setCellValue('A4', $title['header']['row4']);

        // set height column
        $sheet->mergeCells('A6:G6')->setCellValue('A6', $title['header']['row5']);
        $sheet->getRowDimension('6')->setRowHeight(35);
        $sheet->getStyle('A6')->getFont()->setSize($fontBig);

        $sheet->mergeCells('A8:G8')->setCellValue('A8', $title['header']['row6']);
        $sheet->getStyle('A8')->getFont()->setSize($fontMedium);
        $sheet->getRowDimension('8')->setRowHeight(35);

        // apply style
        $sheet->getStyle('A1:A8')->applyFromArray($fontBold);
        $sheet->getStyle('A1:A7')->applyFromArray($horizontalCenter);

        // người nhận - người gửi
        $sheet->mergeCells('A9:D9')
            ->setCellValue('A9', 'Người nhận: ' . $model->customer->name);
        $sheet->mergeCells('E9:G9')
            ->setCellValue('E9', 'Người gửi: Công Ty MBT');
        $sheet->getRowDimension('9')->setRowHeight(25);

        // địa chỉ - người gửi
        $sheet->mergeCells('A10:D10')
            ->setCellValue('A10', 'Địa chỉ: ' . $model->customer->address);
        $sheet->mergeCells('E10:G10')
            ->setCellValue('E10', 'Người gửi: ' . $model->user->name);
        $sheet->getRowDimension('10')->setRowHeight(25);

        // điện thoại - bộ phận
        $sheet->mergeCells('A11:D11')
            ->setCellValue('A11', 'Điện thoại/Fax: ' . $model->customer->mobile);
        $sheet->mergeCells('E11:G11')
            ->setCellValue('E11', 'Bộ phận: Phòng Kinh doanh');
        $sheet->getRowDimension('11')->setRowHeight(25);

//        $sheet->getStyle('A9:G11')->applyFromArray($borderThin);

        // mô tả
        $sheet->mergeCells('A12:G12')
            ->setCellValue('A12', $title['desc'])
            ->getStyle('A12')
            ->applyFromArray($verticalCenter)
            ->getAlignment()
            ->setWrapText(true);
        $sheet->getRowDimension('12')->setRowHeight(50);

        // bảng chi tiết báo giá
        $sheet->setCellValue('A13', 'STT');
        $sheet->setCellValue('B13', 'Tên hàng hóa, quy cách');
        $sheet->setCellValue('C13', 'Đơn vị');
        $sheet->setCellValue('D13', 'Số lượng');
        $sheet->setCellValue('E13', 'Đơn giá');
        $sheet->setCellValue('F13', 'Thành tiền');
        $sheet->setCellValue('G13', 'Ghi chú');

        $sheet->getStyle('A13:G13')
            ->applyFromArray(array_merge($fontBold, $verAndHorCenter));
        $sheet->getRowDimension('13')->setRowHeight(30);

        // STT
        $sheet->setCellValue('A14', '01')
            ->getStyle('A14')
            ->applyFromArray($horizontalCenter);

        // công suất
        $power = sprintf("MBA %sKVA-%s/%skV \n%s", $model->power, $model->voltage_input, $model->voltage_output, $model->formatSkin());
        $sheet->setCellValue('B14', $power)
            ->getStyle('B14')
            ->getAlignment()
            ->setWrapText(true);

        // kiểu
        $sheet->setCellValue('C14', $model->formatType())
            ->getStyle('C14')
            ->applyFromArray($horizontalCenter);

        // số lượng
        $amount = $model->amount < 10 ? '0' . $model->amount : $model->amount;
        $sheet->setCellValue('D14', $amount)
            ->getStyle('D14')
            ->applyFromArray($horizontalCenter);

        // giá
        $price = $model->price * 1000;
        $sheet->setCellValue('E14', $price)
            ->getStyle('E14')
            ->applyFromArray($horizontalRight)
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        // tổng tiền
        $total = $model->total_money * 1000;
        $sheet->setCellValue('F14', $total)
            ->getStyle('F14')
            ->applyFromArray($horizontalRight)
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        // tiêu chuẩn
        $standard = $model->formatStandardReal();
        $sheet->setCellValue('G14', $standard)
            ->getStyle('G14')
            ->applyFromArray($horizontalCenter)
            ->getAlignment()
            ->setWrapText(true);

        // set style
        $sheet->getStyle('A14:G14')->applyFromArray($verticalCenter);

        // tổng tiền trước thuế
        $sheet->mergeCells('A15:E15')
            ->setCellValue('A15', 'Tổng tiền trước thuế')
            ->getStyle('A15')
            ->applyFromArray($horizontalCenter);
        $sheet->setCellValue('F15', $total)
            ->getStyle('F15')
            ->getNumberFormat()
            ->setFormatCode('#,##0');
        $sheet->getRowDimension('15')->setRowHeight(25);

        // VAT
        $sheet->mergeCells('A16:E16')
            ->setCellValue('A16', 'Thuế GTGT 10%')
            ->getStyle('A16')
            ->applyFromArray($horizontalCenter);
        $vat = $model->total_money * 0.1 * 1000;
        $sheet->setCellValue('F16', $vat)
            ->getStyle('F16')
            ->getNumberFormat()
            ->setFormatCode('#,##0');
        $sheet->getRowDimension('16')->setRowHeight(25);

        // tổng tiền sau thếu
        $totalAfterVat = $total + $vat;
        $sheet->mergeCells('A17:E17')
            ->setCellValue('A17', 'Tổng cộng sau thuế')
            ->getStyle('A17')
            ->applyFromArray($horizontalCenter);
        $sheet->setCellValue('F17', $totalAfterVat)
            ->getStyle('F17')
            ->getNumberFormat()
            ->setFormatCode('#,##0');
        $sheet->getRowDimension('17')->setRowHeight(25);

        // ghi chú sau thuế
        $sheet->mergeCells('A18:G18')
            ->setCellValue('A18', 'Giá bán trên đã bao gồm thuế GTGT 10%')
            ->getStyle('A18')
            ->applyFromArray(array_merge($fontItalic, $horizontalCenter));
        $sheet->getRowDimension('18')->setRowHeight(25);

        // set style
        $sheet->getStyle('A15:G17')->applyFromArray($fontBold);
        $sheet->getStyle('A13:G18')->applyFromArray($borderThin);

        // Nội dung các điểm của báo giá
        $terms = 'Điều kiện thanh toán : ' . (empty($model->terms_of_payment) ? 'Thanh toán giá trị còn lại trước khi nhận hàng.' : $model->terms_of_payment);
        $sheet->mergeCells('A19:G19')
            ->setCellValue('A19', $terms);
        $sheet->getRowDimension('19')->setRowHeight(25);

        $temp = 'Thời gian giao hàng: Giao hàng sau 15-20 ngày kể từ ngày nhận được tiền tạm ứng.';
        $sheet->mergeCells('A20:G20')
            ->setCellValue('A20', $temp);
        $sheet->getRowDimension('20')->setRowHeight(25);

        // địa chỉ giao hàng
        $deliveryAt = 'Điều kiện giao hàng: Giao hàng tại kho: ' . $model->delivery_at;
        $sheet->mergeCells('A21:G21')
            ->setCellValue('A21', $deliveryAt);
        $sheet->getRowDimension('21')->setRowHeight(25);

        // thời gian bảo hành
        $guarantee = 'Thời gian bảo hành: ' . ($model->guarantee < 10 ? '0' : '') . $model->guarantee . ' tháng.';
        $sheet->mergeCells('A22:G22')
            ->setCellValue('A22', $guarantee);
        $sheet->getRowDimension('22')->setRowHeight(25);

        // hiệu lực báo giá
        $expired = 'Báo giá này có hiệu lực trong vòng ' . $model->expired . ' ngày kể từ ngày báo giá.';
        $sheet->mergeCells('A23:G23')
            ->setCellValue('A23', $expired);
        $sheet->getRowDimension('23')->setRowHeight(25);

        // kính chào
        $sheet->mergeCells('A24:G24')
            ->setCellValue('A24', $title['footer']['row1'])
            ->getStyle('A24')
            ->getAlignment()
            ->setWrapText(true);
        $sheet->getRowDimension('24')->setRowHeight(50);

        // ngày tháng năm
        $sheet->mergeCells('D26:G26')
            ->setCellValue('D26', 'Hà Nội, ngày ... tháng ... năm ' . date('Y'))
            ->getStyle('D26')
            ->applyFromArray(array_merge($fontItalic, $horizontalCenter));


        // footer
        $sheet->mergeCells('A27:C27')
            ->setCellValue('A27', $title['footer']['confirm'])
            ->getStyle('A27')
            ->applyFromArray(array_merge($fontBold, $horizontalCenter));

        $sheet->mergeCells('D27:G27')
            ->setCellValue('D27', $title['footer']['signature'])
            ->getStyle('D27')
            ->applyFromArray(array_merge($fontBold, $horizontalCenter));

        $sheet->getStyle('A1:G27')->getFont()->setName($fontFamily);
        $sheet->getStyle('A1:G27')->getAlignment()->setIndent(1);

        // page setting
        $sheet->getSheetView()->setZoomScale(125);
        $sheet->getPageSetup()->setFitToPage(true);


        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('logo.png'));
        $drawing->setHeight(36);
        $drawing->setWorksheet($sheet);

        // output file
        $name = sprintf('bao_gia_%s_%s_KH_%s.xlsx', str_slug($model->user->name, '_'), date('d_m_Y'), str_slug($model->customer->name, '_'));
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $name . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');
        $writer->save('php://output');
    }

}
