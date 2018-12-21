
@php
    /**
     * @var $orders \App\Order[]
     * @var $cares \App\Care[]
     * @var $priceQuotations \App\PriceQuotation[]
     */
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    {{$model->name}}
                </h2>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <td>Họ tên</td>
                            <td>{{$model->name}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Lịch sử mua hàng</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div class="table-responsive">
                            @if(count($orders))
                            <table class="table table-striped jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th>Mã ĐH</th>
                                    <th>Ngày vào sản xuất</th>
                                    <th>Giá trị đơn hàng</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $item)
                                        <tr>
                                            <td><b class="text-success">{{$item->code}}</b></td>
                                            <td>{{$item->formatStartDate()}}</td>
                                            <td>{{$item->formatTotalMoney()}}</td>
                                            <td class="text-right"><a href="#" class="btn btn-xs btn-info">Xem</a> </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            @else
                                <div class="alert alert-info">Chưa mua hàng lần nào</div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Lịch sử chăm sóc
                        </h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <div class="table-responsive">
                            @if(count($cares))
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>Nội dung</th>
                                        <th>Ngày gọi</th>
                                        <th>Ngày hẹn</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cares as $item)
                                        <tr>
                                            <td>{{$item->formatStatus()}}</td>
                                            <td>{{$item->formatStartDate()}}</td>
                                            <td>{{$item->formatEndDate()}}</td>
                                            <td class="text-right"><a href="#" class="btn btn-xs btn-info">Xem</a> </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">Chưa gọi chăm sóc lần nào</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Lịch sử báo giá
                        </h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <div class="table-responsive">
                            @if(count($priceQuotations))
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>Ngày báo</th>
                                        <th>Kiểu máy</th>
                                        <th>Ngoại hình</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th>Tình trạng</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($priceQuotations as $item)
                                        <tr>
                                            <td>{{$item->formatQuotationDate()}}</td>
                                            <td>{!! $item->formatType() !!}</td>
                                            <td>{!! $item->formatSkin() !!}</td>
                                            <td>{{$item->amount}}</td>
                                            <td>{{$item->formatTotalMoney()}}</td>
                                            <td>{!! $item->formatStatus() !!}</td>
                                            <td class="text-right"><a href="#" class="btn btn-xs btn-info">Xem</a> </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">Chưa gọi chăm sóc lần nào</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Công nợ
                        </h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <div class="table-responsive">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/customer.js') }}"></script>
@endsection