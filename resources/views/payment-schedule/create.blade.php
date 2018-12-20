@php
    /**
    * @var $order \App\Order
    * @var $data \App\PaymentSchedule[]
    */
@endphp
@extends('layouts.main')

@section('content')

    <div class="right_col" role="main">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Thông tin đơn hàng
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content" style="padding:0">
                        <div class="modal-body" style="padding:0">
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <td>Nhân viên</td>
                                    <td>{!! $order->formatUser()!!}</td>
                                </tr>
                                <tr>
                                    <td>Khách hàng</td>
                                    <td>{!! $order->formatCustomer() !!}</td>
                                </tr>
                                <tr>
                                    <td>Khu vực</td>
                                    <td>{!! $order->formatCustomerCity() !!}</td>
                                </tr>

                                <tr>
                                    <td>Ngày vào sản xuất</td>
                                    <td>{!! $order->formatStartDate() !!}</td>
                                </tr>
                                <tr>
                                    <td>Ngày giao hàng dự tính</td>
                                    <td>{!! $order->formatShippedDate() !!}</td>
                                </tr>
                                <tr>
                                    <td>Ngày giao hàng thực tế</td>
                                    <td>{!! $order->formatShippedDateReal() !!}</td>
                                </tr>
                                <tr>
                                    <td>Địa chỉ lắp đặt</td>
                                    <td>{!! $order->setup_at !!}</td>
                                </tr>
                                <tr>
                                    <td>Địa chỉ giao hàng</td>
                                    <td>{!! $order->delivery_at !!}</td>
                                </tr>
                                <tr>
                                    <td>Số lượng x đơn giá = thành tiền </td>
                                    <td>{!!  sprintf('%s x %s = <code>%s</code>', $order->amount, $order->formatPrice(), $order->formatTotalMoney()) !!}</td>
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
                                    <td>{!! $order->formatType() !!}</td>
                                </tr>

                                <tr>
                                    <td>Ngoại hình máy</td>
                                    <td>{!! $order->formatSkin() !!}</td>
                                </tr>
                                <tr>
                                    <td>Bảo hành</td>
                                    <td>{{$order->guarantee}} tháng</td>
                                </tr>
                                <tr>
                                    <td>Tiêu chuẩn</td>
                                    <td>{!! $order->formatStandard() !!}</td>
                                </tr>
                                <tr>
                                    <td>Trạng thái đơn hàng</td>
                                    <td>{!! $order->formatStatus() !!}</td>
                                </tr>
                                <tr>
                                    <td>Ghi chú đơn hàng</td>
                                    <td>{!! $order->note !!}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Lịch trình thanh toán
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th>No.</th>
                                    <th>Số tiền</th>
                                    <th>Ngày thanh toán</th>
                                    <th>Kiểu</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($data))
                                    @foreach($data as $item)
                                        <tr>
                                            <td style="width: 50px">{{$item->id}}</td>
                                            <td>{{$item->formatMoney()}}</td>
                                            <td>
                                                {!! $item->formatDate() !!}
                                            </td>
                                            <td>{!! $item->formatStatus() !!}</td>
                                        </tr>
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
                        <h2>
                            Tạo mới
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="display-error" class="alert alert-danger hidden"></div>
                        <form id="payment-schedule-form" onsubmit="return;">
                            @include('payment-schedule._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/payment-schedule.js') }}"></script>
@endsection
