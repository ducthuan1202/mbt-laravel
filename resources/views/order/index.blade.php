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
                    @if($searchParams['status'] == \App\Order::SHIPPED_STATUS)
                    <div class="col-md-6 col-sm-6 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">ĐÃ GIAO</span>
                        <div class="count red">{{$count[\App\Order::SHIPPED_STATUS]['count']}}</div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">TỔNG GIÁ TRỊ</span>
                        <div class="count green">{{Common::formatMoney($count[\App\Order::SHIPPED_STATUS]['total'])}}</div>
                    </div>
                    @endif

                    @if($searchParams['status'] == \App\Order::NOT_SHIPPED_STATUS)
                        <div class="col-md-6 col-sm-6 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                            <span class="count_top">CHƯA GIAO</span>
                            <div class="count red">{{$count[\App\Order::NOT_SHIPPED_STATUS]['count']}}</div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                            <span class="count_top">TỔNG GIÁ TRỊ</span>
                            <div class="count green">{{Common::formatMoney($count[\App\Order::NOT_SHIPPED_STATUS]['total'])}}</div>
                        </div>
                    @endif

                    @if($searchParams['status'] == \App\Order::CANCEL_STATUS)
                        <div class="col-md-6 col-sm-6 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                            <span class="count_top">ĐÃ HỦY</span>
                            <div class="count red">{{$count[\App\Order::CANCEL_STATUS]['count']}}</div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                            <span class="count_top">TỔNG GIÁ TRỊ</span>
                            <div class="count green">{{Common::formatMoney($count[\App\Order::CANCEL_STATUS]['total'])}}</div>
                        </div>
                    @endif

                </div>

                @if($message = Session::get('success'))
                    <div class="alert alert-success">{{$message}}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr class="headings">
                                <th style="vertical-align: middle">No.</th>
                                <th style="vertical-align: middle">Khách hàng</th>
                                <th style="vertical-align: middle">Khu vực</th>
                                <th style="vertical-align: middle">Công suất</th>
                                <th style="vertical-align: middle">Số lượng</th>
                                <th style="vertical-align: middle">Tạm ứng</th>
                                <th style="vertical-align: middle">Đã thanh toán</th>
                                <th style="vertical-align: middle">Công nợ</th>
                                <th style="vertical-align: middle" class="text-center">Ngày giao hàng</th>
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
                                        <td><b style="color:#ff5722">{!! $item->formatCustomer('<br/>') !!}</b></td>
                                        <td>{!! $item->formatCustomerCity() !!}</td>
                                        <td>
                                            {{$item->power }} kvA <br/>
                                            {{$item->voltage_input }} - {{$item->voltage_output }} kv
                                        </td>
                                        <td>{{$item->amount .' '. ($item->product_type == \App\Order::CABIN_SKIN ? 'tủ' : 'máy') }} </td>
                                        <td>{{$item->formatPrePay() }}</td>
                                        <td>{{$item->formatPayment() }}</td>
                                        <td>{{$item->formatDebt() }}</td>
                                        <td class="text-center">{{$item->formatShippedDate()}}</td>
                                        <td>{!! $item->formatStatus() !!}</td>
                                        <td><b class="text-success">{{$item->formatUser()}}</b></td>
                                        <td class="text-right">

                                        </td>
                                        <td class="text-right" style="min-width: 100px">
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
                    <div role="pagination">{{$data->appends($searchParams)->links()}}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderModal"></div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/order.js') }}"></script>
    <script>getCitiesAndCustomersByUser();</script>
@endsection