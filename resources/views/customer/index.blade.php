@php
    use App\Helpers\Common;
    /**
     * @var $data \App\Customer[]
     */
@endphp

@php $title = 'Khách hàng'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$title}}</h2>
                <a class="btn btn-success pull-right" href="{{route('customers.create')}}">
                    <i class="fa fa-plus"></i> Thêm mới
                </a>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('customer._search')

                <div class="row tile_count text-center" style="margin-top: 0;">
                    <div class="col-md-4 col-sm-4 col-xs-12 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">Tổng số</span>
                        <div class="count blue">{{Common::formatNumber($data->total())}}</div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">Đã mua</span>
                        <div class="count green">{{Common::formatNumber($hasBuy)}}</div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 tile_stats_count" style="margin-bottom: 0">
                        <span class="count_top">Chưa mua</span>
                        <div class="count ">{{Common::formatNumber($data->total() - $hasBuy)}}</div>
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
                            <th>Họ tên</th>
                            <th>Khu vực</th>
                            <th>Số điện thoại</th>
                            <th>Công ty</th>
                            <th>Nhân viên KD</th>
                            <th class="text-right" style="width: 80px">Trạng thái</th>
                            <th class="text-right">Ngày tạo</th>
                            <th style="max-width: 150px"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($data))
                            @foreach($data as $index => $item)
                                <tr>
                                    <td>{{ $index + $data->firstItem() }}</td>
                                    <td>
                                        <strong class="text-success">{{$item->name}}</strong>
                                        <p style="font-size: 11px">{{$item->position}}</p>
                                    </td>
                                    <td>{{$item->formatCity()}}</td>
                                    <td>
                                        <a class="text-primary" href="tel:{{$item->mobile}}"><b>{{$item->mobile}}</b></a><br/>
                                    </td>
                                    <td>
                                        <span>{{$item->formatCompany()}}</span>
                                    </td>
                                    <td>
                                        <strong class="text-danger">{!! $item->formatUser() !!}</strong>
                                    </td>
                                    <td>
                                        {!! $item->formatStatus() !!}
                                    </td>
                                    <td class="text-right">{{$item->formatCreatedAt()}}</td>
                                    <td class="text-right" style="max-width: 150px">
                                        <a href="{{route('customers.show', $item->id)}}" class="btn btn-primary btn-xs">
                                            <i class="fa fa-folder"></i> Xem
                                        </a>
                                        <a href="{{route('customers.edit', $item->id)}}" class="btn btn-info btn-xs">
                                            <i class="fa fa-pencil"></i> Sửa
                                        </a>
                                        @can('admin')
                                            <a onclick="MBT_Customer.delete({{$item->id}})" class="btn btn-danger btn-xs">
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
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/customer.js') }}"></script>
    <script>getCitiesByUser()</script>
@endsection