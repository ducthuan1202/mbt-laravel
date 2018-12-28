@php
use \App\Helpers\Common;
/**
* @var $revenuePrePayWeek \App\Order[]
* @var $revenuePrePayMonth \App\Order[]
* @var $paymentWeek \App\PaymentSchedule[]
* @var $paymentMonth \App\PaymentSchedule[]
*/
@endphp
@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <h1>Báo cáo</h1>

        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2>Doanh thu tuần này</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        @if($revenuePrePayWeek && count($revenuePrePayWeek))
                            @php $_sumTmp = 0 @endphp
                            <h4 class="text-center">Tạm ứng đơn hàng</h4>
                            <div class="table-responsive">
                                <table class="table jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th style="vertical-align: middle">No.</th>
                                        <th style="vertical-align: middle">Đơn hàng</th>
                                        <th style="vertical-align: middle">Khách hàng</th>
                                        <th style="vertical-align: middle">Tạm ứng</th>
                                        <th style="vertical-align: middle">Ngày thanh toán</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($revenuePrePayWeek as $index => $item)
                                            @php $_sumTmp += $item->prepay; @endphp
                                            <tr>
                                                <td style="width: 50px">{{$index + 1}}</td>
                                                <td><b style="color:#ff5722">{{$item->code}}</b></td>
                                                <td>{!! $item->formatCustomer() !!}</td>
                                                <td>{{$item->formatPrePay() }}</td>
                                                <td>{!! $item->formatStartDate() !!}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2" class="bg-info text-right">Tổng</td>
                                            <td colspan="3" class="bg-warning">{{Common::formatMoney($_sumTmp)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if($paymentWeek && count($paymentWeek))
                            @php $_sumTmp = 0 @endphp
                            <h4 class="text-center">Thanh toán công nợ</h4>
                            <div class="table-responsive">
                                <table class="table jambo_table bulk_action">
                                    <thead>
                                        <tr class="headings">
                                            <th style="vertical-align: middle">No.</th>
                                            <th style="vertical-align: middle">Đơn hàng</th>
                                            <th style="vertical-align: middle">Khách hàng</th>
                                            <th style="vertical-align: middle">Công nợ</th>
                                            <th style="vertical-align: middle">Ngày thanh toán</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($paymentWeek as $index => $item)
                                        @php $_sumTmp += $item->money; @endphp
                                        <tr>
                                            <td style="width: 50px">{{$index + 1}}</td>
                                            <td>{{$item->order->code}}</td>
                                            <td>{!! $item->order->formatCustomer() !!}</td>
                                            <td>{!! $item->formatMoney() !!}</td>
                                            <td>{{$item->formatDate() }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="bg-info text-right">Tổng</td>
                                        <td colspan="3" class="bg-warning">{{Common::formatMoney($_sumTmp)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="col-xs-12 col-sm-6">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2>Doanh thu tháng này</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        @if($revenuePrePayMonth && count($revenuePrePayMonth))
                            @php $_sumTmp = 0 @endphp
                            <h4 class="text-center">Tạm ứng đơn hàng</h4>
                            <div class="table-responsive">
                                <table class="table jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th style="vertical-align: middle">No.</th>
                                        <th style="vertical-align: middle">Đơn hàng</th>
                                        <th style="vertical-align: middle">Khách hàng</th>
                                        <th style="vertical-align: middle">Tạm ứng</th>
                                        <th style="vertical-align: middle">Ngày thanh toán</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($revenuePrePayMonth as $index => $item)
                                        @php $_sumTmp += $item->prepay; @endphp
                                        <tr>
                                            <td style="width: 50px">{{$index + 1}}</td>
                                            <td><b style="color:#ff5722">{{$item->code}}</b></td>
                                            <td>{!! $item->formatCustomer() !!}</td>
                                            <td>{{$item->formatPrePay() }}</td>
                                            <td>{!! $item->formatStartDate() !!}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="bg-info text-right">Tổng</td>
                                        <td colspan="3" class="bg-warning">{{Common::formatMoney($_sumTmp)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if($paymentMonth && count($paymentMonth))
                            @php $_sumTmp = 0 @endphp
                            <h4 class="text-center">Thanh toán công nợ</h4>
                            <div class="table-responsive">
                                <table class="table jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th style="vertical-align: middle">No.</th>
                                        <th style="vertical-align: middle">Đơn hàng</th>
                                        <th style="vertical-align: middle">Khách hàng</th>
                                        <th style="vertical-align: middle">Công nợ</th>
                                        <th style="vertical-align: middle">Ngày thanh toán</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($paymentMonth as $index => $item)
                                        @php $_sumTmp += $item->money; @endphp
                                        <tr>
                                            <td style="width: 50px">{{$index + 1}}</td>
                                            <td>{{$item->order->code}}</td>
                                            <td>{!! $item->order->formatCustomer() !!}</td>
                                            <td>{!! $item->formatMoney() !!}</td>
                                            <td>{{$item->formatDate() }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="bg-info text-right">Tổng</td>
                                        <td colspan="3" class="bg-warning">{{Common::formatMoney($_sumTmp)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/report.js') }}"></script>
@endsection