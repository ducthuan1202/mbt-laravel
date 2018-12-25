@php
    /**
     * @var $model \App\PriceQuotation
     */
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Báo giá: #{{$model->code}}
                </h2>
                <a class="btn btn-dark pull-right" href="{{route('quotations.index')}}">
                    <i class="fa fa-reply"></i> Trở về
                </a>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="bg-warning">
                            <td>Nhân viên kinh doanh chăm sóc</td>
                            <td>{{$model->formatUser()}}</td>
                        </tr>
                        <tr class="bg-warning">
                            <td>Trạng thái báo giá</td>
                            <td>{!! $model->formatStatus() !!}</td>
                        </tr>
                        <tr class="bg-warning">
                            <td>Mã báo giá</td>
                            <td>{{$model->code}}</td>
                        </tr>
                        <tr class="bg-warning">
                            <td>Khách hàng</td>
                            <td>{!! $model->formatCustomer() !!}</td>
                        </tr>
                        <tr>
                            <td>Khu vực</td>
                            <td>{!! $model->formatCustomerCity() !!}</td>
                        </tr>
                        <tr>
                            <td>Địa chỉ lắp đặt</td>
                            <td>{!! $model->setup_at !!}</td>
                        </tr>
                        <tr>
                            <td>Địa chỉ giao hàng</td>
                            <td>{!! $model->delivery_at !!}</td>
                        </tr>
                        <tr>
                            <td>Số lượng</td>
                            <td>{{$model->amount}}</td>
                        </tr>
                        <tr>
                            <td>Giá báo</td>
                            <td>{{$model->formatPrice()}}</td>
                        </tr>
                        <tr>
                            <td>Công suất</td>
                            <td>{{ $model->power }} kvA</td>
                        </tr>

                        <tr>
                            <td>Điện áp vào</td>
                            <td>{{ $model->voltage_input }} kv</td>
                        </tr>
                        <tr>
                            <td>Điện áp ra</td>
                            <td>{{ $model->voltage_output}} kv</td>
                        </tr>
                        <tr>
                            <td>Kiểu máy</td>
                            <td>{!! $model->formatType() !!}</td>
                        </tr>

                        <tr>
                            <td>Ngoại hình máy</td>
                            <td>{!! $model->formatSkin() !!}</td>
                        </tr>
                        <tr>
                            <td>Tiêu chuẩn</td>
                            <td>{!! $model->formatStandard() !!}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
