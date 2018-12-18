
@php
    /**
     * @var $data \App\Customer[]
     */
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Khách Hàng
                    <small>Tổng số <b>{{$data->total()}}</b></small>
                </h2>

                <a class="btn btn-success pull-right" href="{{route('customers.create')}}">
                    <i class="fa fa-plus"></i> Thêm mới
                </a>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('customer._search')

                @if($message = Session::get('success'))
                    <div class="alert alert-success">{{$message}}</div>
                @endif

                <div class="ln_solid"></div>

                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                        <tr class="headings">
                            <th>No.</th>
                            <th>Họ tên</th>
                            <th>Số điện thoại</th>
                            <th>Chức vụ</th>
                            <th>Công ty</th>
                            <th>Khu vực</th>
                            <th>NVKD chăm sóc</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($data))
                            @foreach($data as $item)
                                <tr>
                                    <td style="width: 50px">{{$item->id}}</td>
                                    <td>
                                        <b class="text-success">{{$item->name}}</b>
                                        <p style="font-size: 11px">{{$item->code}}</p>
                                    </td>
                                    <td><a class="text-primary" href="tel:{{$item->mobile}}"><b>{{$item->mobile}}</b></a></td>
                                    <td><span class="text-primary">{{$item->position}}</span></td>
                                    <td><span class="text-primary">{{$item->company}}</span></td>
                                    <td>{{$item->formatCity()}}</td>
                                    <td><b class="text-success">{{$item->formatUser()}}</b></td>
                                    <td>{!! $item->formatStatus() !!}</td>
                                    <td class="text-right" style="min-width: 220px">
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
                    <div role="pagination">{{$data->links()}}</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/customer.js') }}"></script>
@endsection