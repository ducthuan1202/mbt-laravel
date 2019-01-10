@php
    use \App\Helpers\Common;
    /**
    * @var $data \App\User
    */
@endphp
@php $title = 'Báo Cáo'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">
        <div class="clearfix"></div>

        <div class="alert alert-success">
            <h3>{{$data->name}}</h3>
            <h4>Thống kê {{count($data->orders)}} lượt ra đơn hàng thời gian từ: {{$date}}</h4>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2><i class="fa fa-users"></i> Đơn hàng: <small>{{$date}}</small></h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th>No.</th>
                                    <th>Ngày tạo</th>
                                    <th>Khách hàng</th>
                                    <th>Công ty</th>
                                    <th>Khu vực</th>
                                    <th>Trạng thái</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($data)
                                    @foreach($data->orders as $index => $item)
                                        <tr>
                                            <td>{{$index + 1}}</td>
                                            <td>{{$item->formatStartDate()}}</td>
                                            <td>{{$item->formatCustomer()}}</td>
                                            <td>{{$item->customer->formatCompany()}}</td>
                                            <td>{{$item->customer->formatCity()}}</td>
                                            <td>{!! $item->formatStatus() !!}</td>
                                            <td class="text-right">
                                                <a href="{{route('payment-schedules.index', $item->id)}}" class="btn btn-primary btn-xs">
                                                    <i class="fa fa-folder"></i> Chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="100%">Không có dữ liệu</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
