@php
    /**
     * @var $data \App\Debt[]
     */
    use App\Helpers\Common;
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Công Nợ
                    <small>Tổng số <b>{{$data->total()}}</b></small>
                </h2>

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
                            <th>Khách hàng</th>
                            <th>Khu vực</th>
                            <th>Ngày tạo</th>
                            <th>Đơn hàng</th>
                            <th>Số nợ</th>
                            <th>Nhân viên KD</th>
                            <th>Kiểu công nợ</th>
                            <th>Trạng Thái</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($data))
                            @foreach($data as $index => $item)
                                <tr>
                                    <td style="width: 50px">{{$index + 1}}</td>

                                    <td>{!! $item->formatCustomer() !!}</td>
                                    <td>{{$item->formatCustomerCity()}}</td>
                                    <td>{{$item->formatDateCreate()}}</td>
                                    <td>{{$item->formatOrder()}}</td>
                                    <td>{{$item->formatMoney()}}</td>
                                    <td>{{$item->formatCustomerUser()}}</td>
                                    <td>{!! $item->formatType() !!}</td>
                                    <td>{!! $item->formatStatus() !!}</td>
                                    <td class="text-right">
                                        @can('admin')
                                            @if($item->status == \App\Debt::OLD_STATUS)
                                            <a href="{{route('debts.edit', $item->id)}}" class="btn btn-info btn-xs">
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
                    <div role="pagination">{{$data->links()}}</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/debt.js') }}"></script>
    <script>MBT_Debt.getCustomerByCityIndex()</script>
@endsection