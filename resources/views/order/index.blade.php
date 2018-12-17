@php
    /**
     * @var $data \App\Order[]
     */
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Đơn hàng
                    <small>Tổng số <b>{{$data->total()}}</b></small>
                </h2>

                <a class="btn btn-success pull-right" href="{{route('orders.create')}}">
                    <i class="fa fa-plus"></i> Thêm mới
                </a>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('order._search')

                @if($message = Session::get('success'))
                    <div class="alert alert-success">{{$message}}</div>
                @endif

                <div class="ln_solid"></div>

                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr class="headings">
                                <th>No.</th>
                                <th>Mã ĐH</th>
                                <th>Khách hàng</th>
                                <th>Khu vực</th>
                                <th>Giá trị ĐH</th>
                                <th>Ngày vào SX</th>
                                <th>Trạng thái</th>
                                <th>Nhân viên KD</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data))
                                @foreach($data as $item)
                                    <tr>
                                        <td style="width: 50px">{{$item->id}}</td>
                                        <td><b class="text-danger">{{$item->code}}</b></td>
                                        <td><b style="color:#ff5722">{!! $item->formatCustomer() !!}</b></td>
                                        <td>{!! $item->formatCustomerCity() !!}</td>
                                        <td>{{$item->formatTotalMoney()}}</td>
                                        <td>{{$item->formatStartDate()}}</td>
                                        <td>{!! $item->formatStatus() !!}</td>
                                        <td><b class="text-success">{{$item->formatUser()}}</b></td>
                                        <td class="text-right" style="min-width: 150px">
                                            <a onclick="MBT_Order.getDetail({{$item->id}})" class="btn btn-primary btn-xs">
                                                <i class="fa fa-folder"></i> Xem
                                            </a>
                                            <a href="{{route('orders.edit', $item->id)}}" class="btn btn-info btn-xs">
                                                <i class="fa fa-pencil"></i> Sửa
                                            </a>
                                            @can('admin')
                                                <a onclick="MBT_Order.delete({{$item->id}})" class="btn btn-danger btn-xs">
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

    <div class="modal fade" id="orderModal"></div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/order.js') }}"></script>
    <script>MBT_Order.getCustomerByCityIndex()</script>
@endsection