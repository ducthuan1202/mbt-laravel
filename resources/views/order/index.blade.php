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
                    <table class="table table-striped jambo_table bulk_action" style="width: 3000px">
                        <thead>
                            <tr class="headings">
                                <th style="vertical-align: middle">No.</th>
                                <th style="vertical-align: middle">Nhân viên KD</th>
                                <th style="vertical-align: middle">Khách hàng</th>
                                <th style="vertical-align: middle">Ngày vào sản xuất</th>
                                <th style="vertical-align: middle">Ngày ĐK giao</th>
                                <th style="vertical-align: middle">Ngày thay đổi</th>
                                <th style="vertical-align: middle">Ngày giao thực tế</th>
                                <th style="vertical-align: middle">Số máy</th>
                                <th style="vertical-align: middle">Công suất</th>
                                <th style="vertical-align: middle">Điện áp vào</th>
                                <th style="vertical-align: middle">Điện áp ra</th>
                                <th style="vertical-align: middle">Số lượng</th>
                                <th style="vertical-align: middle">Tiêu chuẩn sản xuất</th>
                                <th style="vertical-align: middle">Tiêu chuẩn xuất máy</th>
                                <th style="vertical-align: middle">Tổ đấu</th>
                                <th style="vertical-align: middle">Nơi lắp</th>
                                <th style="vertical-align: middle">Nơi giao</th>
                                <th style="vertical-align: middle">Bảo hành</th>
                                <th style="vertical-align: middle">Ngoại hình</th>
                                <th style="vertical-align: middle">Ghi chú 1</th>
                                <th style="vertical-align: middle">Đơn giá</th>
                                <th style="vertical-align: middle">Thanh toán</th>
                                <th style="vertical-align: middle">Thành tiền</th>
                                <th style="vertical-align: middle">VAT</th>
                                <th style="vertical-align: middle">Chênh lệch VAT</th>
                                <th style="vertical-align: middle">Tạm ứng</th>
                                <th style="vertical-align: middle">Hạn thanh toán</th>
                                <th style="vertical-align: middle">Đã tạm ứng</th>
                                <th style="vertical-align: middle">Số tiền còn lại</th>
                                <th style="vertical-align: middle">Số ngày vào KHSX</th>
                                <th style="vertical-align: middle">Quá hạn giao máy</th>
                                <th style="vertical-align: middle">Loại hàng</th>
                                <th style="vertical-align: middle">Lý do xuất</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data))
                                @foreach($data as $index => $item)

                                    <tr>
                                        <td style="width: 50px">{{$index + 1}}</td>
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
                                                        <a href="{{route('payment-schedules.index', $item->id)}}"><i class="fa fa-folder"></i> Xem đơn hàng</a>
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
                                        <td>---</td>
                                        <td>{{$item->formatShippedDateReal()}}</td>
                                        <td>{{$item->product_number}}</td>
                                        <td>{{$item->power}}</td>
                                        <td>{{$item->voltage_input}}</td>
                                        <td>{{$item->voltage_output}}</td>
                                        <td>{{$item->amount}}</td>
                                        <td>{{$item->formatStandard()}}</td>
                                        <td>{{$item->formatStandardReal()}}</td>
                                        <td>{{$item->formatGroupWork()}}</td>
                                        <td>{{$item->setup_at}}</td>
                                        <td>{{$item->delivery_at}}</td>
                                        <td>{{$item->guarantee}} tháng</td>
                                        <td>{{$item->formatSkin()}}</td>
                                        <td>{!! $item->note !!}</td>
                                        <td>{{$item->formatPrice()}}</td>
                                        <td>---</td>
                                        <td>{{$item->formatTotalMoney()}}</td>
                                        <td>{{$item->formatVat()}}</td>
                                        <td>{{$item->formatDifferenceVat()}}</td>
                                        <td>{{$item->prepay_required}}</td>
                                        <td>hạn tt</td>
                                        <td>{{$item->formatPrePay()}}</td>
                                        <td>{{$item->formatDebt() }}</td>
                                        <td>10</td>
                                        <td>10</td>
                                        <td>{{$item->formatProductType()}}</td>
                                        <td>{{$item->formatConditionPass()}}</td>
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