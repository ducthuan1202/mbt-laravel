@php
    /**
     * @var $data \App\PriceQuotation[]
     */
    use App\Helpers\Common;
@endphp

@php $title = 'Báo Giá'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$title}}</h2>

                <a class="btn btn-success pull-right" href="{{route('quotations.create')}}">
                    <i class="fa fa-plus"></i> Thêm mới
                </a>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('quotation._search')

                <div class="row tile_count text-center" style="margin-top: 0;">
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">Tổng số</span>
                        <div class="count blue">{{Common::formatNumber($data->total())}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">THÀNH CÔNG</span>
                        <div class="count green">{{Common::formatNumber($counter[\App\PriceQuotation::SUCCESS_STATUS])}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">ĐANG THEO</span>
                        <div class="count purple">{{Common::formatNumber($counter[\App\PriceQuotation::PENDING_STATUS])}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">THẤT BẠI</span>
                        <div class="count red">{{Common::formatNumber($counter[\App\PriceQuotation::FAIL_STATUS])}}</div>
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
                            <th style="vertical-align: middle">Ngày</th>
                            <th style="vertical-align: middle">Khách hàng</th>
                            <th style="vertical-align: middle">CS</th>
                            <th style="vertical-align: middle;width: 120px">Điện áp <br/>đầu vào</th>
                            <th style="vertical-align: middle;width: 120px">Điện áp <br/> đầu ra</th>
                            <th style="vertical-align: middle;width: 120px">Tiêu chuẩn<br/>sản xuất</th>
                            <th style="vertical-align: middle;width: 120px">Tiêu chuẩn<br/>xuất máy</th>
                            <th style="vertical-align: middle">Tổ đấu</th>
                            <th style="vertical-align: middle">Nơi lắp</th>
                            <th style="vertical-align: middle">Đơn giá</th>
                            <th style="vertical-align: middle">Thành tiền</th>
                            <th style="vertical-align: middle;width: 120px">Hiệu lực<br/> báo giá</th>
                            <th style="vertical-align: middle;width: 120px">Điều khoản<br/>thanh toán</th>
                            <th style="vertical-align: middle;">Trạng thái</th>
                            <th style="vertical-align: middle;width: 120px">Nhân viên KD</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($data))
                            @foreach($data as $index => $item)
                                <tr>
                                    <td>{{ $index + $data->firstItem() }}</td>
                                    <td>
                                        {{$item->formatQuotationDate()}}<br/>
                                        <div class="btn-group">
                                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle btn-xs"
                                                    type="button" aria-expanded="false">Hành động <span class="caret"></span>
                                            </button>
                                            <ul role="menu" class="dropdown-menu">

                                                @if($item->status === \App\PriceQuotation::SUCCESS_STATUS)
                                                    <li>
                                                        @if($item->order)
                                                            <a href="{{route('orders.detail_by_code', ['code' =>$item->code])}}" class="text-info"><i class="fa fa-shopping-cart"></i> Xem đơn hàng</a>
                                                        @else
                                                            <a href="{{route('orders.create', ['orderId' => $item->id])}}" class="text-success"><i class="fa fa-shopping-cart"></i> Tạo đơn hàng</a>
                                                        @endif
                                                    </li>
                                                    <li class="divider"></li>
                                                @endif
                                                <li>
                                                    <a href="{{route('quotations.show', $item->id)}}"><i class="fa fa-folder"></i> Xem báo giá</a>
                                                </li>
                                                <li>
                                                    <a href="{{route('quotations.edit', $item->id)}}"><i class="fa fa-pencil"></i> Sửa báo giá</a>
                                                </li>
                                                @can('admin')
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a onclick="MBT_PriceQuotation.delete({{$item->id}})"><i class="fa fa-trash-o"></i>  Xóa báo giá</a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{route('quotations.show', $item->id)}}" style="text-decoration: underline">
                                            {!! $item->formatCustomer('<br/>') !!}
                                        </a>
                                    </td>
                                    <td>{{$item->power}}</td>
                                    <td>{{$item->voltage_input}}</td>
                                    <td>{{$item->voltage_output}}</td>
                                    <td>{{$item->formatStandard()}}</td>
                                    <td>{{$item->formatStandardReal()}}</td>
                                    <td>{{$item->formatGroupWork()}}</td>
                                    <td>{{$item->delivery_at}}</td>
                                    <td>{{$item->formatPrice()}}</td>
                                    <td>{{$item->formatTotalMoney()}}</td>
                                    <td>{{empty($item->expired) ? '' : $item->expired.' ngày'}}</td>
                                    <td>{{$item->formatTermsOfPayment()}}</td>
                                    <td>{!! $item->formatStatus() !!}</td>
                                    <td><strong class="text-danger">{!! $item->formatUser() !!}</strong></td>

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

    <div class="modal fade" id="quotationModal"></div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/quotation.js') }}"></script>
    <script>getCitiesAndCustomersByUser();</script>
@endsection