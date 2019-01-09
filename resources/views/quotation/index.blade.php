@php
    /**
     * @var $data \App\PriceQuotation[]
     */
    use App\Helpers\Common;
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>Báo Giá</h2>

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
                            <th style="width: 50px">No.</th>
                            <th>Ngày báo giá</th>
                            <th>Khách hàng</th>
                            <th>Khu vực</th>
                            <th>Trạng thái</th>
                            <th>Nhân viên KD</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($data))
                            @foreach($data as $index => $item)
                                <tr>
                                    <td>{{ $index + $data->firstItem() }}</td>
                                    <td>
                                        {{$item->formatQuotationDate()}}<br/>
                                        @if($item->status === \App\PriceQuotation::SUCCESS_STATUS)
                                            @if($item->order)
                                                <span class="label label-default">Đã tạo ĐH</span>
                                            @else
                                                <a href="{{route('orders.create', ['orderId' => $item->id])}}" class="label label-warning">Tạo ĐH</a>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('quotations.show', $item->id)}}" style="text-decoration: underline">
                                            {!! $item->formatCustomer('<br/>') !!}
                                        </a>
                                    </td>
                                    <td>
                                        {!! $item->formatCustomerCity() !!}
                                    </td>
                                    <td>
                                        {!! $item->formatStatus() !!}
                                    </td>
                                    <td>
                                        <strong class="text-danger">{!! $item->formatUser() !!}</strong>
                                    </td>
                                    <td>
                                        @can('admin')
                                            <a onclick="MBT_PriceQuotation.delete({{$item->id}})" class="btn btn-danger btn-xs">
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

    <div class="modal fade" id="quotationModal"></div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/quotation.js') }}"></script>
    <script>getCitiesAndCustomersByUser();</script>
@endsection