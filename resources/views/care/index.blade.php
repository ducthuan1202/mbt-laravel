@php
    use App\Helpers\Common;

    /**
     * @var $data \App\Care[]
     */
    $title = 'Chăm sóc khách hàng';
@endphp

@extends('layouts.main')

@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$title}}</h2>

                <a class="btn btn-success pull-right" href="{{route('cares.create')}}">
                    <i class="fa fa-plus"></i> Thêm mới
                </a>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('care._search')

                <div class="row tile_count text-center" style="margin-top: 0;">
                    <div class="col-md-3 col-sm-3 col-xs-6 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">Tổng số lượt chăm sóc</span>
                        <div class="count blue">{{Common::formatNumber($data->total())}}</div>
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
                                    <td>{{ $index + $data->firstItem() }}</td>
                                    <td>
                                        <strong class="text-success">{!! $item->formatCustomer() !!}</strong>
                                    </td>
                                    <td>{!! $item->formatCustomerCity() !!}</td>
                                    <td>{{$item->formatStartDate()}}</td>
                                    <td>{{$item->formatEndDate()}}</td>
                                    <td><span class="badge">{{$item->formatStatus()}}</span></td>
                                    <td>
                                        <strong class="text-danger">{!! $item->formatUser() !!}</strong>
                                    </td>
                                    <td style="width: 200px">{!! $item->customer_note !!}</td>
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
                    <div role="pagination">{{$data->appends($searchParams)->links()}}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-lg" id="careModelForm"></div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/care.js') }}"></script>
    <script>getCitiesAndCustomersByUser();</script>
@endsection