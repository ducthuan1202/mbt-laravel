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
            <h4>Thống kê {{count($data->cares)}} lượt chăm sóc khách hàng thời gian từ: {{$date}}</h4>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2><i class="fa fa-users"></i> Chăm sóc khách hàng: <small>{{$date}}</small></h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th>No.</th>
                                    <th>Ngày gọi</th>
                                    <th>Khách hàng</th>
                                    <th>SĐT</th>
                                    <th>Công ty</th>
                                    <th>Khu vực</th>
                                    <th>Nội dung chăm sóc</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($data)
                                    @foreach($data->cares as $index => $item)
                                        <tr>
                                            <td>{{$index + 1}}</td>
                                            <td>{{$item->formatStartDate()}}</td>
                                            <td>{{$item->customer->name}}</td>
                                            <td>{{$item->customer->mobile}}</td>
                                            <td>{{$item->customer->company}}</td>
                                            <td>{{$item->customer->formatCity()}}</td>
                                            <td>{!! $item->formatStatus() !!}</td>
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
