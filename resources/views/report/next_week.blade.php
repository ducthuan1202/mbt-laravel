@php
    use \App\Helpers\Common;
    /**
    * @var $revenue \App\Debt[]
    * @var $payment \App\PaymentSchedule[]
    */
@endphp
@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">

        <h1>Tuần sau</h1>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2><i class="fa fa-slideshare"></i> Thanh toán nợ cũ</h2>
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
                                        <th style="vertical-align: middle">Ngày hẹn thanh toán</th>
                                        <th style="vertical-align: middle">Khách hàng</th>
                                        <th style="vertical-align: middle">Khu vực</th>
                                        <th style="vertical-align: middle">Số tiền</th>
                                        <th style="vertical-align: middle">NVKD</th>
                                        <th style="vertical-align: middle">Trạng thái</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($revenue as $index => $item)
                                        @php $_sumTmp += $item->total_money; @endphp
                                        <tr>
                                            <td style="width: 50px">{{$index + 1}}</td>
                                            <td>{{$item->formatDatePay()}}</td>
                                            <td>{!! $item->formatCustomer() !!}</td>
                                            <td>{{$item->formatCustomerCity()}}</td>
                                            <td>{{$item->formatMoney()}}</td>
                                            <td>{{$item->formatCustomerUser()}}</td>
                                            <td>{!! $item->formatType() !!}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4" class="bg-info text-right">Tổng</td>
                                        <td colspan="3" class="bg-warning">{{Common::formatMoney($_sumTmp)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
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
                                        <th style="vertical-align: middle">Ngày hẹn thanh toán</th>
                                        <th style="vertical-align: middle">Đơn hàng</th>
                                        <th style="vertical-align: middle">Khách hàng</th>
                                        <th style="vertical-align: middle">Khu vực</th>
                                        <th style="vertical-align: middle">Số tiền</th>
                                        <th style="vertical-align: middle">Ghi chú</th>
                                        <th style="vertical-align: middle">NVKD</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payment as $index => $item)
                                        @php $_sumTmp += $item->money; @endphp
                                        <tr>
                                            <td style="width: 50px">{{$index + 1}}</td>
                                            <td>{{$item->formatDate() }}</td>
                                            <td>{{$item->order->code}}</td>
                                            <td>{!! $item->order->formatCustomer() !!}</td>
                                            <td>{!! $item->order->formatCustomerCity() !!}</td>
                                            <td>{!! $item->formatMoney() !!}</td>
                                            <td>{{$item->note}}</td>
                                            <td>{{$item->order->user->name}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5" class="bg-info text-right">Tổng</td>
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
