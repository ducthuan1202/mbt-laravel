@php
    /**
     * @var $data \App\Debt[]
     */
    use App\Helpers\Common;
@endphp

@php $title = 'Công Nợ'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$title}}</h2>
                @can('admin')
                    <a class="btn btn-success pull-right" href="{{route('debts.create')}}">
                        <i class="fa fa-plus"></i> Thêm mới
                    </a>
                @endcan

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('debt._search')

                <div class="row tile_count text-center" style="margin-top: 0;">
                    <div class="col-md-4 col-sm-4 col-xs-4 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">Tổng số</span>
                        <div class="count blue">{{Common::formatNumber($data->total())}}</div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">ĐÃ THANH TOÁN</span>
                        <div class="count green">{{Common::formatNumber(0)}}</div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">CHƯA THANH TOÁN</span>
                        <div class="count red">{{Common::formatNumber(0)}}</div>
                    </div>
                </div>

                @if($message = Session::get('success'))
                    <div class="alert alert-success">{{$message}}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                        <tr class="headings">
                            <th>No.</th>
                            <th>trạng thái</th>
                            <th>Khách hàng</th>
                            <th>Công ty</th>
                            <th>Khu vực</th>
                            <th>Số nợ</th>
                            <th>Đã thanh toán</th>
                            <th>Còn lại</th>
                            <th>Nhân viên KD</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($data))
                            @foreach($data as $index => $item)
                                <tr>
                                    <td>{{ $index + $data->firstItem() }}</td>
                                    <td>{!! $item->order ? $item->order->formatStatus() : '' !!}</td>
                                    <td>
                                        <a href="{{route('customers.show', ['id'=>$item->customer->id])}}">
                                            {!! $item->formatCustomer() !!}
                                        </a>
                                    </td>
                                    <td>{{$item->customer->formatCompany()}}</td>
                                    <td>{{$item->customer->formatCity()}}</td>
                                    <td>{{$item->formatMoney()}}</td>
                                    <td>{{$item->formatHasPaid()}}</td>
                                    <td>{{$item->formatNotPaid()}}</td>
                                    <td>{{$item->formatCustomerUser()}}</td>
                                    <td class="text-right">
                                        @can('admin')
                                            @if($item->status == \App\Debt::OLD_STATUS)
                                                <a href="{{route('debts.show', $item->id)}}"
                                                   class="btn btn-success btn-xs">
                                                    <i class="fa fa-folder"></i> Xem
                                                </a>
                                                <a href="{{route('debts.edit', $item->id)}}"
                                                   class="btn btn-info btn-xs">
                                                    <i class="fa fa-pencil"></i> Sửa
                                                </a>
                                            @endif
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
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/debt.js') }}"></script>
    <script>getCitiesAndCustomersByUser();</script>
@endsection