@php
    /**
     * @var $data \App\Order[]
     */
    use App\Helpers\Common;
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Đơn hàng
                    <small>Tổng số <b>{{$data->total()}}</b></small>
                </h2>

                @can('admin')
                    <a class="btn btn-success pull-right" href="{{route('orders.create')}}">
                        <i class="fa fa-plus"></i> Thêm mới
                    </a>
                @endcan

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('order._search')

                <div class="row tile_count text-center" style="margin-top: 0;">
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">Tổng số</span>
                        <div class="count blue">{{Common::formatNumber($data->total())}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">ĐÃ GIAO</span>
                        <div class="count green">{{$count[\App\Order::SHIPPED_STATUS]}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">CHƯA GIAO</span>
                        <div class="count ">{{$count[\App\Order::NOT_SHIPPED_STATUS]}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">ĐÃ HỦY</span>
                        <div class="count ">{{$count[\App\Order::CANCEL_STATUS]}}</div>
                    </div>
                </div>

                @if($message = Session::get('success'))
                    <div class="alert alert-success">{{$message}}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr class="headings">
                                <th style="vertical-align: middle">No.</th>
                                <th style="vertical-align: middle">Mã ĐH</th>
                                <th style="vertical-align: middle">Khách hàng</th>
                                <th style="vertical-align: middle">Khu vực</th>
                                <th style="vertical-align: middle">Công nợ</th>
                                <th style="vertical-align: middle">Đã thanh toán</th>
                                <th style="vertical-align: middle" class="text-center">Ngày giao hàng<br/>dự tính</th>
                                <th style="vertical-align: middle">Trạng thái</th>
                                <th style="vertical-align: middle">Nhân viên KD</th>
                                <th style="vertical-align: middle"></th>
                                <th style="vertical-align: middle"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data))
                                @foreach($data as $index => $item)

                                    <tr>
                                        <td style="width: 50px">{{$index + 1}}</td>
                                        <td>
                                            <b class="text-danger">{{$item->code}}</b> <br/>
                                            <a href="{{route('payment-schedules.index',$item->id)}}" class="btn btn-warning btn-xs">
                                                <i class="fa fa-thumb-tack"></i> Lịch trình thanh toán
                                            </a>
                                        </td>
                                        <td><b style="color:#ff5722">{!! $item->formatCustomer() !!}</b></td>
                                        <td>{!! $item->formatCustomerCity() !!}</td>
                                        <td>{{$item->formatDebt() }}</td>
                                        <td>{{$item->formatPayment() }}</td>
                                        <td class="text-center">{{$item->formatShippedDate()}}</td>
                                        <td>{!! $item->formatStatus() !!}</td>
                                        <td><b class="text-success">{{$item->formatUser()}}</b></td>
                                        <td class="text-right">

                                        </td>
                                        <td class="text-right" style="min-width: 100px">
                                            {{--<a onclick="MBT_Order.getDetail({{$item->id}})" class="btn btn-primary btn-xs">--}}
                                                {{--<i class="fa fa-folder"></i> Xem--}}
                                            {{--</a>--}}
                                            <a href="{{route('payment-schedules.index', $item->id)}}" class="btn btn-primary btn-xs">
                                                <i class="fa fa-folder"></i> Xem
                                            </a>
                                            <a href="{{route('orders.edit', $item->id)}}" class="btn btn-info btn-xs">
                                                <i class="fa fa-pencil"></i> Sửa
                                            </a>
                                            @can('admin')
                                                <a onclick="MBT_Order.delete({{$item->id}})" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-trash-o"></i> Xóa
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="100%">Không có dữ liệu.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if(count($data))
                    <div role="pagination">{{$data->links()}}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderModal"></div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/order.js') }}"></script>
    <script>MBT_Order.getCustomerByCityIndex();</script>
@endsection