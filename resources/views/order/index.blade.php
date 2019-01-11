@php
    /**
     * @var $data \App\Order[]
     */
    use App\Helpers\Common;
@endphp

@php $title = 'Đơn hàng'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$title}}</h2>

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

                    @if(empty($searchParams['status']))
                        <div class="col-md-3 tile_stats_count" style="margin-bottom: 0">
                            <span class="count_top">TỔNG</span>
                            <div class="count dark">{{$data->total()}}</div>
                        </div>
                        <div class="col-md-3 tile_stats_count" style="margin-bottom: 0">
                            <span class="count_top">ĐÃ GIAO</span>
                            <div class="count green">{{$count[\App\Order::SHIPPED_STATUS]['count']}}</div>
                        </div>
                        <div class="col-md-3 tile_stats_count" style="margin-bottom: 0">
                            <span class="count_top">CHƯA GIAO</span>
                            <div class="count blue">{{$count[\App\Order::NOT_SHIPPED_STATUS]['count']}}</div>
                        </div>
                        <div class="col-md-3 tile_stats_count" style="margin-bottom: 0">
                            <span class="count_top">ĐÃ HỦY</span>
                            <div class="count red">{{$count[\App\Order::CANCEL_STATUS]['count']}}</div>
                        </div>
                    @endif

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
                                <th style="vertical-align: middle">Nhân viên KD</th>
                                <th style="vertical-align: middle">Khách hàng</th>
                                <th style="vertical-align: middle">Ngày vào sản xuất</th>
                                <th style="vertical-align: middle">Ngày ĐK giao</th>
                                <th style="vertical-align: middle">Số máy</th>
                                <th style="vertical-align: middle">Công suất</th>
                                <th style="vertical-align: middle">Số lượng</th>
                                <th style="vertical-align: middle">Thành tiền</th>
                                <th style="vertical-align: middle">Đã thanh toán</th>
                                <th style="vertical-align: middle">Số tiền còn lại</th>
                                <th style="vertical-align: middle">Đk giao hàng</th>
                                <th style="vertical-align: middle">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data))
                                @foreach($data as $index => $item)
                                    <tr>
                                        <td>{{ $index + $data->firstItem() }}</td>
                                        <td><b class="text-success">{{$item->formatUser()}}</b></td>
                                        <td>
                                            <a href="{{route('payment-schedules.index', $item->id)}}" style="text-decoration: underline">
                                                {!! $item->formatCustomer('<br/>') !!}
                                            </a>
                                            <br/>

                                            <div class="btn-group">
                                                <button data-toggle="dropdown" class="btn btn-default dropdown-toggle btn-xs"
                                                        type="button" aria-expanded="false">Hành động <span class="caret"></span>
                                                </button>
                                                <ul role="menu" class="dropdown-menu">

                                                    <li>
                                                        {{--<a href="{{route('payment-schedules.index', $item->id)}}"><i class="fa fa-folder"></i> Xem đơn hàng</a>--}}
                                                        <a href="{{route('orders.show', $item->id)}}"><i class="fa fa-folder"></i> Xem đơn hàng</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{route('orders.edit', $item->id)}}"><i class="fa fa-pencil"></i> Sửa đơn hàng</a>
                                                    </li>
                                                    @can('admin')
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a onclick="MBT_Order.delete({{$item->id}})"><i class="fa fa-trash-o"></i>  Xóa đơn hàng</a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>

                                        </td>
                                        <td>{{$item->formatStartDate()}}</td>
                                        <td>{{$item->formatShippedDate()}}</td>
                                        <td>{{$item->product_number}}</td>
                                        <td>{{$item->power}}<br/>{{$item->voltage_input}}<br/>{{$item->voltage_output}}</td>
                                        <td>{{$item->amount}}</td>
                                        <td>{{$item->formatTotalMoney()}}</td>
                                        <td>{{$item->formatHasPaid()}}</td>
                                        <td>{{$item->formatNotPaid()}}</td>
                                        <td>{!! $item->formatPrePayRequired() !!}</td>
                                        <td>{!! $item->formatStatus() !!}</td>
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