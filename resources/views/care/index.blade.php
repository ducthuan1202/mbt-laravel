@php
    /**
     * @var $data \App\Care[]
     */
    use App\Helpers\Common;
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Chăm Sóc Khách Hàng
                </h2>

                <a class="btn btn-success pull-right" href="{{route('cares.create')}}">
                    <i class="fa fa-plus"></i> Thêm mới
                </a>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('care._search')

                <div class="row tile_count text-center" style="margin-top: 0;">
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">Tổng số</span>
                        <div class="count blue">{{Common::formatNumber($data->total())}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">THÀNH CÔNG</span>
                        <div class="count green">{{Common::formatNumber(0)}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">ĐANG THEO</span>
                        <div class="count purple">{{Common::formatNumber(0)}}</div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">THẤT BẠI</span>
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
                            <th>Ngày gọi</th>
                            <th>Ngày hẹn</th>
                            <th>Nội dung chăm sóc</th>
                            <th>Nhân viên KD</th>
                            <th>Ghi chú</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($data))
                            @foreach($data as $index => $item)
                                <tr>
                                    <td style="width: 50px">{{$index + 1}}</td>
                                    <td>{!! $item->formatCustomer() !!}</td>
                                    <td>{!! $item->formatCustomerCity() !!}</td>
                                    <td>{{$item->formatStartDate()}}</td>
                                    <td>{{$item->formatEndDate()}}</td>
                                    <td><span class="badge">{{$item->formatStatus()}}</span></td>
                                    <td>{!! $item->formatUser() !!}</td>
                                    <td>{!! $item->customer_note !!}</td>
                                    <td class="text-right" style="min-width: 150px">
                                        <a href="{{route('cares.edit', $item->id)}}" class="btn btn-info btn-xs">
                                            <i class="fa fa-pencil"></i> Sửa
                                        </a>
                                        @can('admin')
                                            <a onclick="MBT_Care.delete({{$item->id}})" class="btn btn-danger btn-xs">
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

    <div class="modal fade bs-example-modal-lg" id="careModelForm"></div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/care.js') }}"></script>
    <script>getCitiesByUser()</script>
@endsection