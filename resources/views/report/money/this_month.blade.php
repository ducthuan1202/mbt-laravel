@php
    use \App\Helpers\Common;
    /**
    * @var $revenue \App\Order[]
    * @var $payment \App\PaymentSchedule[]
    * @var $debtPaid \App\Debt[]
    */
@endphp
@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">

        <h1>Tháng này</h1>

        <div class="x_panel tile">
            <div class="x_title">
                <h2><i class="fa fa-slideshare"></i> Thanh toán nợ cũ</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                @if($debtPaid && count($debtPaid))
                    @php $_sumTmp = 0 @endphp
                    <div class="table-responsive">
                        <table class="table jambo_table bulk_action">
                            <thead>
                            <tr class="headings">
                                <th style="vertical-align: middle">No.</th>
                                <th style="vertical-align: middle">Ngày thanh toán</th>
                                <th style="vertical-align: middle">Số tiền</th>
                                <th style="vertical-align: middle">Khách hàng</th>
                                <th style="vertical-align: middle">Khu vực</th>
                                <th style="vertical-align: middle">NVKD</th>
                                <th style="vertical-align: middle">Ghi chú</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($debtPaid as $index => $item)
                                @php $_sumTmp += $item->total_money; @endphp
                                <tr>
                                    <td style="width: 50px">{{$index + 1}}</td>
                                    <td>{{$item->formatDatePay()}}</td>
                                    <td>{{$item->formatMoney()}}</td>
                                    <td>{!! $item->formatCustomer() !!}</td>
                                    <td>{{$item->formatCustomerCity()}}</td>
                                    <td>{{$item->formatCustomerUser()}}</td>
                                    <td>{{$item->note}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="bg-info text-right">Tổng</td>
                                <td colspan="5" class="bg-warning">{{Common::formatMoney($_sumTmp)}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="x_panel tile">
            <div class="x_title">
                <h2><i class="fa fa-shopping-cart"></i> Tạm ứng đơn hàng</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                @if($revenue && count($revenue))
                    @php $_sumTmp = 0 @endphp
                    <div class="table-responsive">
                        <table class="table jambo_table bulk_action">
                            <thead>
                            <tr class="headings">
                                <th style="vertical-align: middle">No.</th>
                                <th style="vertical-align: middle">Ngày thanh toán</th>
                                <th style="vertical-align: middle">Số tiền</th>
                                <th style="vertical-align: middle">Khách hàng</th>
                                <th style="vertical-align: middle">Khu vực</th>
                                <th style="vertical-align: middle">Đơn hàng</th>
                                <th style="vertical-align: middle">NVKD</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($revenue as $index => $item)
                                @php $_sumTmp += $item->prepay; @endphp
                                <tr>
                                    <td style="width: 50px">{{$index + 1}}</td>
                                    <td>{!! $item->formatStartDate() !!}</td>
                                    <td>{{$item->formatPrePay() }}</td>
                                    <td>{{$item->formatCustomer() }}</td>
                                    <td>{!! $item->formatUser() !!}</td>
                                    <td><a href="{{route('payment-schedules.index', $item->id)}}" style="text-decoration: underline">{{$item->code}}</a></td>
                                    <td>{{$item->formatUser()}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="bg-info text-right">Tổng</td>
                                <td colspan="5" class="bg-warning">{{Common::formatMoney($_sumTmp)}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>


        <div class="x_panel tile">
            <div class="x_title">
                <h2><i class="fa fa-shopping-cart"></i> Thanh toán đơn hàng</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                @if($payment && count($payment))
                    @php $_sumTmp = 0 @endphp
                    <div class="table-responsive">
                        <table class="table jambo_table bulk_action">
                            <thead>
                            <tr class="headings">
                                <th style="vertical-align: middle">No.</th>
                                <th style="vertical-align: middle">Ngày thanh toán</th>
                                <th style="vertical-align: middle">Sô tiền</th>
                                <th style="vertical-align: middle">Khách hàng</th>
                                <th style="vertical-align: middle">Khu vực</th>
                                <th style="vertical-align: middle">Đơn hàng</th>
                                <th style="vertical-align: middle">NVKD</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payment as $index => $item)
                                @php $_sumTmp += $item->money; @endphp
                                <tr>
                                    <td style="width: 50px">{{$index + 1}}</td>
                                    <td>{{$item->formatDate() }}</td>
                                    <td>{{$item->formatMoney()}}</td>
                                    <td>{{isset($item->order) ? $item->order->formatCustomer() : ''}}</td>
                                    <td>{{isset($item->order) ? $item->order->formatCustomerCity() : ''}}</td>
                                    <td>{{$item->order->code}}</td>
                                    <td>
                                        @if(isset($item->order))
                                            <a href="{{route('payment-schedules.index', $item->order->id)}}" style="text-decoration: underline">{{$item->order->code}}</a>
                                        @endif
                                    </td>
                                    <td>{{$item->order->formatUser()}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="bg-info text-right">Tổng</td>
                                <td colspan="5" class="bg-warning">{{Common::formatMoney($_sumTmp)}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
