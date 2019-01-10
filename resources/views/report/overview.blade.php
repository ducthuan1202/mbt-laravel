@php
    use \App\Helpers\Common;
    /**
    * @var $date string
    */
@endphp
@php $title = 'Báo Cáo'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">

        <div class="x_panel tile">
            <div class="x_title">
                <h2><i class="fa fa-bar-chart-o"></i> Báo cáo tổng quan: <small>{{$date}}</small></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="table-responsive">
                    <table class="table jambo_table bulk_action">
                        <thead>
                            <tr class="headings">
                                <th>Nội dung thống kê</th>
                                <th>Số lượng</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tổng số khách hàng mới</td>
                                <td>{{$customersData}}</td>
                                <td class="text-right">
                                    <a href="{{route('report.customers', ['date'=>$date])}}" class="btn btn-xs btn-success">
                                        <i class="fa fa-folder"></i> Xem thống kê chi tiết
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Tổng số lượt CSKH</td>
                                <td>{{$caresDate}}</td>
                                <td class="text-right"><a href="{{route('report.cares', ['date'=>$date])}}" class="btn btn-xs btn-success"><i class="fa fa-folder"></i> Xem thống kê chi tiết</a></td>
                            </tr>
                            <tr>
                                <td>Tổng số lượt báo giá</td>
                                <td>{{$quotationsData}}</td>
                                <td class="text-right"><a href="{{route('report.quotations', ['date'=>$date])}}" class="btn btn-xs btn-success"><i class="fa fa-folder"></i> Xem thống kê chi tiết</a></td>
                            </tr>
                            <tr>
                                <td>Tổng số đơn hàng</td>
                                <td>{{$ordersData}}</td>
                                <td class="text-right"><a href="{{route('report.orders', ['date'=>$date])}}" class="btn btn-xs btn-success"><i class="fa fa-folder"></i> Xem thống kê chi tiết</a></td>
                            </tr>
                            <tr>
                                <td>Tổng số công nợ mới</td>
                                <td>{{$debtsData}} ({{\App\Helpers\Common::formatMoney($sumOldDebt->total)}})</td>
                                <td class="text-right"><a href="" class="btn btn-xs btn-success"><i class="fa fa-folder"></i> Xem thống kê chi tiết</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection
