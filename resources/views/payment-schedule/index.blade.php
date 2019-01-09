@php
    use \App\Helpers\Common;
    /**
    * @var $order \App\Order
    * @var $data \App\PaymentSchedule[]
    */
    $sum = 0;
@endphp

@extends('layouts.main')

@section('content')

    <div class="right_col" role="main">


        @if($message = Session::get('success'))
            <div class="alert alert-success">{{$message}}</div>
        @endif

        <div class="clearfix">
            <a href="{{route('orders.index')}}" class="btn btn-dark btn-xs pull-right">
                <i class="fa fa-reply"></i> Trở về
            </a>
            <a href="{{route('orders.edit', $order->id)}}" class="btn btn-info btn-xs">
                <i class="fa fa-pencil"></i> Sửa
            </a>
        </div>


        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Đơn hàng #{{$order->code}}
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content" style="padding:0">

                        <div class="modal-body" style="padding:0">
                            <table class="table table-bordered">
                                <tbody>
                                <tr class="bg-warning">
                                    <td>Nhân viên</td>
                                    <td>{!! $order->formatUser()!!}</td>
                                </tr>
                                <tr class="bg-warning">
                                    <td>Mã đơn hàng</td>
                                    <td><kbd>{{$order->code}}</kbd></td>
                                </tr>
                                <tr class="bg-warning">
                                    <td>Trạng thái đơn hàng</td>
                                    <td>{!! $order->formatStatus() !!}</td>
                                </tr>
                                <tr class="bg-warning">
                                    <td>Khách hàng</td>
                                    <td>{!! $order->formatCustomer() !!}</td>
                                </tr>
                                <tr>
                                    <td>Khu vực</td>
                                    <td>{{$order->formatCustomerCity()}}</td>
                                </tr>
                                <tr>
                                    <td>Ngày vào sản xuất</td>
                                    <td>{{$order->formatStartDate()}}</td>
                                </tr>
                                <tr>
                                    <td>Ngày giao hàng dự tính</td>
                                    <td>{{$order->formatShippedDate()}}</td>
                                </tr>
                                <tr>
                                    <td>Ngày giao hàng thực tế</td>
                                    <td>{{$order->formatShippedDateReal()}}</td>
                                </tr>
                                <tr>
                                    <td>Địa chỉ lắp đặt</td>
                                    <td>{{$order->setup_at}}</td>
                                </tr>
                                <tr>
                                    <td>Địa chỉ giao hàng</td>
                                    <td>{{$order->delivery_at}}</td>
                                </tr>
                                <tr>
                                    <td>Số lượng</td>
                                    <td>{{$order->amount}}</td>
                                </tr>
                                <tr>
                                    <td>Giá bán</td>
                                    <td>{{$order->formatPrice()}}</td>
                                </tr>
                                <tr>
                                    <td>Thành tiền</td>
                                    <td>{{$order->formatTotalMoney()}}</td>
                                </tr>
                                <tr class="bg-warning">
                                    <td>VAT</td>
                                    <td><span class="red">{{$order->formatVat()}}</span></td>
                                </tr>
                                <tr class="bg-warning">
                                    <td>Tạm ứng</td>
                                    <td>
                                        <span class="blue">{{$order->formatPrePay()}}</span>
                                    </td>
                                </tr>
                                <tr class="bg-warning">
                                    <td>Còn lại</td>
                                    <td>{{$order->formatPaymentPreShip()}}</td>
                                </tr>
                                <tr>
                                    <td>Công suất</td>
                                    <td>{{ $order->power }} kvA</td>
                                </tr>

                                <tr>
                                    <td>Điện áp vào</td>
                                    <td>{{ $order->voltage_input }} kv</td>
                                </tr>
                                <tr>
                                    <td>Điện áp ra</td>
                                    <td>{{ $order->voltage_output}} kv</td>
                                </tr>

                                <tr>
                                    <td>Kiểu máy</td>
                                    <td>{{$order->formatType()}}</td>
                                </tr>

                                <tr>
                                    <td>Ngoại hình máy</td>
                                    <td>{{$order->formatSkin()}}</td>
                                </tr>
                                <tr>
                                    <td>Bảo hành</td>
                                    <td>{{$order->guarantee}} tháng</td>
                                </tr>
                                <tr>
                                    <td>Tiêu chuẩn</td>
                                    <td>{{$order->formatStandard()}}</td>
                                </tr>

                                <tr>
                                    <td>Ghi chú đơn hàng</td>
                                    <td style="width: 60%">{!! $order->note !!}</td>
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
                                    <th>Kiểu</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($data))

                                    @foreach($data as $item)
                                        @php if($item->status == \App\PaymentSchedule::PAID_STATUS) $sum += $item->money; @endphp
                                        <tr>
                                            <td {{(!empty($item->note)) ? 'rowspan=2' : ''}} style="vertical-align: middle; width: 150px">{{$item->formatDate()}}</td>
                                            <td>{{$item->formatMoney()}}</td>
                                            <td>{!! $item->formatStatus() !!}</td>
                                            <td style="width: 30px">
                                                <button class="btn btn-xs btn-outline" onclick="MBT_PaymentSchedule.openForm({{$item->id}})">
                                                    <i class="fa fa-pencil"></i> Sửa
                                                </button>
                                            </td>
                                        </tr>
                                        @if(!empty($item->note))
                                            <tr>
                                                <td colspan="3">
                                                    <span style="font-weight: bold; text-decoration: underline; padding-right: 10px;">Ghi chú:</span>
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

                <div class="x_panel">
                    <div class="x_title">
                        <h2>Thêm mới 1 lịch thanh toán cho đơn hàng</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="display-error" class="alert alert-danger hidden"></div>
                        <form id="payment-schedule-form" onsubmit="return;">
                            @include('payment-schedule._form')
                        </form>
                    </div>
                </div>

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
                                    <td class="text-right">{{$order->formatTotalMoney()}}</td>
                                </tr>
                                <tr>
                                    <td>Tạm ứng</td>
                                    <td class="text-right">{{$order->formatPrePay()}}</td>
                                </tr>
                                <tr>
                                    <td>VAT</td>
                                    <td class="text-right">{{$order->formatVat()}}</td>
                                </tr>
                                <tr>
                                    <td>Đã thanh toán</td>
                                    <td class="text-right">{{Common::formatMoney($sum)}}</td>
                                </tr>
                                <tr>
                                    <td>Còn lại</td>
                                    <td class="text-right">{{Common::formatMoney($order->getTotalMoneyWithoutPayment() -$sum)}}</td>
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
