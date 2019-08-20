@php
/**
 * @var \App\PriceQuotation[] $data
 * @var array $counter
 * @var array $sumMoney
 */
use App\Helpers\Common;
use Illuminate\Support\Arr;
use App\PriceQuotation;

$counterSuccess = Arr::has($counter, PriceQuotation::SUCCESS_STATUS) ? Arr::get($counter, PriceQuotation::SUCCESS_STATUS, 0) : 0;
$counterPending = Arr::has($counter, PriceQuotation::PENDING_STATUS, 0) ? Arr::get($counter, PriceQuotation::PENDING_STATUS, 0) : 0;
$counterFail = Arr::has($counter, PriceQuotation::FAIL_STATUS, 0) ? Arr::get($counter, PriceQuotation::FAIL_STATUS, 0) : 0;


$sumMoneySuccess = Arr::has($sumMoney, PriceQuotation::SUCCESS_STATUS) ? Arr::get($sumMoney, PriceQuotation::SUCCESS_STATUS, 0) : 0;
$sumMoneyPending = Arr::has($sumMoney, PriceQuotation::PENDING_STATUS, 0) ? Arr::get($sumMoney, PriceQuotation::PENDING_STATUS, 0) : 0;
$sumMoneyFail = Arr::has($sumMoney, PriceQuotation::FAIL_STATUS, 0) ? Arr::get($sumMoney, PriceQuotation::FAIL_STATUS, 0) : 0;

$sumAll = $sumMoneySuccess + $sumMoneyPending + $sumMoneyFail;

@endphp

@php $title = 'Báo Giá'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">

        <div class="x_panel">

            <div class="x_content">

                <div class="row tile_count text-center" style="margin-top: 0;">
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">Tổng số</span>
                        <div class="count blue">{{Common::formatNumber($data->total())}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">THÀNH CÔNG</span>
                        <div class="count green">{{Common::formatNumber($counterSuccess)}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">ĐANG THEO</span>
                        <div class="count purple">{{Common::formatNumber($counterPending)}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">THẤT BẠI</span>
                        <div class="count red">{{Common::formatNumber($counterFail)}}</div>
                    </div>
                </div>

                <hr/>

                <div class="row tile_count text-center" style="margin-top: 0;">
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <div class="count blue" style="font-size: 2em">{{Common::formatNumber($sumAll)}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <div class="count green" style="font-size: 2em">{{Common::formatNumber($sumMoneySuccess)}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <div class="count purple" style="font-size: 2em">{{Common::formatNumber($sumMoneyPending)}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <div class="count red" style="font-size: 2em">{{Common::formatNumber($sumMoneyFail)}}</div>
                    </div>
                </div>
            </div>
        </div>


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
                            <th style="vertical-align: middle">Khu vực</th>
                            <th style="vertical-align: middle">Công suất</th>
                            <th style="vertical-align: middle">Nơi lắp</th>
                            <th style="vertical-align: middle">Số lượng</th>
                            <th style="vertical-align: middle">Thành tiền</th>
                            <th style="vertical-align: middle;width: 120px">Hiệu lực<br/> báo giá</th>
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
                                                    <a href="{{route('quotations.print', $item->id)}}"><i class="fa fa-print"></i> In báo giá</a>
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
                                    <td>
                                        {{ $item->formatCustomerCity() }}
                                    </td>
                                    <td>
                                        {{$item->power}}<br/>
                                        {{$item->voltage_input}}<br/>
                                        {{$item->voltage_output}}
                                    </td>
                                    <td>{{$item->delivery_at}}</td>
                                    <td>{{$item->amount}}</td>
                                    <td>{{$item->formatTotalMoney()}}</td>
                                    <td>{{empty($item->expired) ? '' : $item->expired.' ngày'}}</td>
                                    <td>{!! $item->formatStatus() !!}</td>
                                    <td><strong class="text-danger text-truncate">{!! $item->formatUser() !!}</strong></td>

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