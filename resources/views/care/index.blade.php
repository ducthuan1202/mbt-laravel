@php
/**
 * @var $data \App\Care[]
 */
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Chăm Sóc Khách Hàng
                    <small>Tổng số <b>{{$data->count()}}</b></small>
                </h2>

                <a class="btn btn-success pull-right" href="{{route('cares.create')}}">
                    <i class="fa fa-plus"></i> Thêm mới
                </a>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('care._search')

                @if($message = Session::get('success'))
                    <div class="alert alert-success">{{$message}}</div>
                @endif

                <div class="ln_solid"></div>

                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                        <tr class="headings">
                            <th>No.</th>
                            <th>Khách hàng</th>
                            <th>Khu vực</th>
                            <th>Ngày gọi - Ngày hẹn</th>
                            <th>Nội dung chăm sóc</th>
                            <th>Ghi chú</th>
                            <th>Nhân viên</th>
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

                                    <td>{{$item->formatStartDate()}} - {{$item->formatEndDate()}}</td>
                                    <td>{!! $item->formatStatus() !!}</td>
                                    <td>{!! $item->customer_note !!}</td>
                                    <td>{!! $item->formatUser() !!}</td>
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
    <script>MBT_Care.getCustomerByCityIndex()</script>
@endsection