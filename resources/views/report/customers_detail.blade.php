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
            <h4>Thống kê tiếp cận {{count($data->customers)}} khách hàng mới thời gian từ: {{$date}}</h4>
        </div>


        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2><i class="fa fa-users"></i> Đã mua {{count($customersThisWeek['hasBuy'])}}</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th style="vertical-align: middle">No.</th>
                                    <th style="vertical-align: middle">Khách hàng</th>
                                    <th style="vertical-align: middle">SĐT</th>
                                    <th style="vertical-align: middle">Công ty</th>
                                    <th style="vertical-align: middle">Khu vực</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($customersThisWeek['hasBuy'])
                                    @foreach($customersThisWeek['hasBuy'] as $index => $item)
                                        <tr>
                                            <td>{{$index + 1}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->mobile}}</td>
                                            <td>{{$item->formatCompany()}}</td>
                                            <td>{{$item->formatCity()}}</td>
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


            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2><i class="fa fa-users"></i> Chưa mua {{count($customersThisWeek['noBuy'])}}</h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th style="vertical-align: middle">No.</th>
                                    <th style="vertical-align: middle">Khách hàng</th>
                                    <th style="vertical-align: middle">SĐT</th>
                                    <th style="vertical-align: middle">Công ty</th>
                                    <th style="vertical-align: middle">Khu vực</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($customersThisWeek['noBuy'])
                                    @foreach($customersThisWeek['noBuy'] as $index => $item)
                                        <tr>
                                            <td>{{$index + 1}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->mobile}}</td>
                                            <td>{{$item->formatCompany()}}</td>
                                            <td>{{$item->formatCity()}}</td>
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
