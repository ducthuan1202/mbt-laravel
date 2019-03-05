@php
    use \App\Helpers\Common;
    /**
    * @var $model \App\Order
    * @var $payments \App\PaymentSchedule[]
    */
    $sum = 0;
@endphp

@php $title = 'Đơn hàng'; @endphp

@extends('layouts.main')

@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">
        @if($message = Session::get('success'))
            <div class="alert alert-success">{{$message}}</div>
        @endif

        <div class="clearfix">
            <a href="{{route('orders.edit', $model->id)}}" class="btn btn-info">
                <i class="fa fa-pencil"></i> Sửa
            </a>
            <button onclick="window.history.back()" class="btn btn-default pull-right">
                <i class="fa fa-reply"></i> Quay lại
            </button>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{$title}} #{{$model->code}}</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content" style="padding:0">

                        <div class="modal-body" style="padding:0">
                            <table class="table table-bordered">
                                <tbody>
                                <tr class="bg-warning">
                                    <td>Nhân viên kinh doanh</td>
                                    <td>{!! $model->formatUser()!!}</td>
                                </tr>
                                <tr class="bg-warning">
                                    <td>Trạng thái đơn hàng</td>
                                    <td>{!! $model->formatStatus() !!}</td>
                                </tr>
                                <tr class="bg-warning">
                                    <td>Khách hàng</td>
                                    <td>{!! $model->formatCustomer() !!}</td>
                                </tr>
                                <tr class="bg-warning">
                                    <td>Khu vực</td>
                                    <td>{{$model->formatCustomerCity()}}</td>
                                </tr>
                                <tr class="bg-info">
                                    <td>Ngày vào sản xuất</td>
                                    <td>{{$model->formatStartDate()}}</td>
                                </tr>
                                <tr class="bg-info">
                                    <td>Ngày đăng ký giao hàng</td>
                                    <td>{{$model->formatShippedDate()}}</td>
                                </tr>
                                <tr class="bg-info">
                                    <td>Ngày giao hàng thực tế</td>
                                    <td>{{$model->formatShippedDateReal()}}</td>
                                </tr>
                                <tr class="bg-info">
                                    <td>Số ngày vào sản xuất</td>
                                    <td>{{$model->formatDateBuild()}} ngày</td>
                                </tr>
                                <tr class="bg-info">
                                    <td>Số ngày quá hạn giao hàng</td>
                                    <td>{{$model->formatOutDate()}} ngày</td>
                                </tr>
                                <tr class="bg-info">
                                    <td>Ngày thay đổi</td>
                                    <td>{{$model->formatDateChange()}}</td>
                                </tr>
                                <tr class="bg-info">
                                    <td>Địa chỉ lắp đặt</td>
                                    <td>{{$model->setup_at}}</td>
                                </tr>
                                <tr class="bg-info">
                                    <td>Địa chỉ giao hàng</td>
                                    <td>{{$model->delivery_at}}</td>
                                </tr>
                                <tr class="bg-success">
                                    <td>Số lượng</td>
                                    <td>{{$model->amount}}</td>
                                </tr>
                                <tr class="bg-success">
                                    <td>Giá bán</td>
                                    <td>{{$model->formatPrice()}}</td>
                                </tr>
                                <tr class="bg-success">
                                    <td>Thành tiền</td>
                                    <td>{{$model->formatTotalMoney()}}</td>
                                </tr>
                                <tr class="bg-success">
                                    <td>VAT</td>
                                    <td><span class="red">{{$model->formatVat()}}</span></td>
                                </tr>
                                <tr class="bg-success">
                                    <td>Chênh lệc VAT</td>
                                    <td><span class="red">{{$model->formatDifferenceVat()}}</span></td>
                                </tr>
                                <tr class="bg-success">
                                    <td>Tạm ứng trước khi giao</td>
                                    <td>{{$model->formatPrePayRequiredText()}}</td>
                                </tr>
                                <tr class="bg-success">
                                    <td>Đã tạm ứng</td>
                                    <td><span class="blue">{{$model->formatPrePay()}}</span></td>
                                </tr>
                                <tr class="bg-success">
                                    <td>Còn lại</td>
                                    <td>{{$model->formatPaymentPreShip()}}</td>
                                </tr>
                                <tr class="bg-danger">
                                    <td>Số máy</td>
                                    <td><kbd>{{ $model->product_number }}</kbd></td>
                                </tr>
                                <tr class="bg-danger">
                                    <td>Công suất</td>
                                    <td>{{ $model->power }} kvA</td>
                                </tr>
                                <tr class="bg-danger">
                                    <td>Điện áp vào</td>
                                    <td>{{ $model->voltage_input }} kv</td>
                                </tr>
                                <tr class="bg-danger">
                                    <td>Điện áp ra</td>
                                    <td>{{ $model->voltage_output}} kv</td>
                                </tr>
                                <tr class="bg-danger">
                                    <td>Kiểu máy</td>
                                    <td>{{$model->formatType()}}</td>
                                </tr>
                                <tr class="bg-danger">
                                    <td>Ngoại hình máy</td>
                                    <td>{{$model->formatSkin()}}</td>
                                </tr>
                                <tr class="bg-danger">
                                    <td>Bảo hành</td>
                                    <td>{{$model->guarantee}} tháng</td>
                                </tr>
                                <tr class="bg-danger">
                                    <td>Tiêu chuẩn</td>
                                    <td>{{$model->formatStandard()}}</td>
                                </tr>
                                <tr class="bg-danger">
                                    <td>Lý do xuất</td>
                                    <td>{{$model->formatConditionPass()}}</td>
                                </tr>
                                <tr>
                                    <td>Ghi chú đơn hàng</td>
                                    <td style="width: 60%">{{$model->note}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-7">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Lịch trình thanh toán
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr class="headings">
                                    <th>Ngày thanh toán</th>
                                    <th>Số tiền</th>
                                    <th colspan="2">Kiểu</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($payments))

                                    @foreach($payments as $item)
                                        @php
                                            $hasPaid = ($item->status == \App\PaymentSchedule::PAID_STATUS);
                                            if($hasPaid) $sum += $item->money;
                                        @endphp
                                        <tr class="{{$hasPaid ? 'bg-success' : ''}}">
                                            <td {{(!empty($item->note)) ? 'rowspan=2' : ''}} style="vertical-align: middle; width: 150px">{{$item->formatDate()}}</td>
                                            <td style="vertical-align: middle;">{{$item->formatMoney()}}</td>
                                            <td style="vertical-align: middle;">{!! $item->formatStatus() !!}</td>
                                            @can('admin')
                                                <td style="width: 30px">
                                                    <button class="btn btn-xs btn-outline"
                                                            onclick="MBT_PaymentSchedule.openForm({{$item->id}})">
                                                        <i class="fa fa-pencil"></i> Sửa
                                                    </button>

                                                    <button class="btn btn-xs btn-outline"
                                                            onclick="MBT_PaymentSchedule.deleteForm({{$item->id}})">
                                                        <i class="fa fa-trash"></i> Xóa
                                                    </button>
                                                </td>
                                            @endcan
                                        </tr>
                                        @if(!empty($item->note))
                                            <tr>
                                                <td colspan="3">
                                                    <span
                                                        style="font-weight: bold; text-decoration: underline; padding-right: 10px;">Ghi chú:</span>
                                                    {!! $item->note !!}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="100%">Chưa có lịch trình thanh toán nào.</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @can('admin')
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Thêm mới 1 lịch thanh toán cho đơn hàng</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div id="display-error" class="alert alert-danger hidden"></div>
                            <form id="payment-schedule-form" onsubmit="return;">
                                @include('payment-schedule._form', [
                                    'model'=>$paymentSchedule,
                                    'order'=>$model,
                                    'type'=>\App\PaymentSchedule::ORDER_TYPE
                                ])
                            </form>
                        </div>
                    </div>
                @endcan

                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Tổng quan
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>Tổng đơn hàng</td>
                                    <td class="text-right">{{$model->formatTotalMoney()}}</td>
                                </tr>
                                <tr>
                                    <td>Tạm ứng</td>
                                    <td class="text-right">{{$model->formatPrePay()}}</td>
                                </tr>
                                <tr>
                                    <td>VAT</td>
                                    <td class="text-right">{{$model->formatVat()}}</td>
                                </tr>
                                <tr>
                                    <td>Chênh lệch VAT</td>
                                    <td class="text-right">{{$model->formatDifferenceVat()}}</td>
                                </tr>
                                <tr>
                                    <td>Đã thanh toán</td>
                                    <td class="text-right">{{Common::formatMoney($sum)}}</td>
                                </tr>
                                <tr>
                                    <td>Còn lại</td>
                                    <td class="text-right">{{Common::formatMoney($model->getTotalMoneyWithoutPayment() -$sum)}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentSchedule"></div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/payment-schedule.js') }}"></script>
@endsection
