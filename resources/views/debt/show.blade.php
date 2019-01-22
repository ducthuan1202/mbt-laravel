@php
    use \App\Helpers\Common;
    /**
    * @var $model \App\Debt
    * @var $data \App\PaymentSchedule[]
    */
    $sum = 0;
@endphp
@php $title = 'Công nợ'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')

    <div class="right_col" role="main">

        @if($message = Session::get('success'))
            <div class="alert alert-success">{{$message}}</div>
        @endif

        <div class="clearfix">

            <a href="{{route('debts.edit', ['id' => $model->id])}}" class="btn btn-info">
                <i class="fa fa-pencil"></i> Sửa
            </a>

            <button onclick="window.history.back()" class="btn btn-default pull-right">
                <i class="fa fa-reply"></i>  Quay lại</button>
        </div>


        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            {{$title}} #{{$model->customer->name}}
                        </h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content" style="padding:0">

                        <div class="modal-body" style="padding:0">
                            <table class="table table-bordered">
                                <tbody>
                                <tr class="bg-warning">
                                    <td>Nhân viên</td>
                                    <td>{{$model->customer->formatUser()}}</td>
                                </tr>
                                <tr>
                                    <td>Khách hàng</td>
                                    <td>{{$model->customer->name}}</td>
                                </tr>
                                <tr>
                                    <td>Số điện thoại</td>
                                    <td>{{$model->customer->mobile}}</td>
                                </tr>
                                <tr>
                                    <td>Tổng tiền nợ</td>
                                    <td>{{$model->formatMoney()}}</td>
                                </tr>
                                <tr>
                                    <td>Loại công nợ</td>
                                    <td>{!! $model->formatStatus() !!}</td>
                                </tr>
                                <tr>
                                    <td>Ngày tạo</td>
                                    <td>{{$model->formatDateCreate()}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-7">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Lịch trình thanh toán
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr class="headings">
                                    <th>Ngày thanh toán</th>
                                    <th>Số tiền</th>
                                    <th>Kiểu</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($payments))

                                    @foreach($payments as $item)
                                        @php
                                            $hasPaid = ($item->status == \App\PaymentSchedule::PAID_STATUS);
                                            if($hasPaid) $sum += $item->money;
                                        @endphp
                                        <tr class="{{$hasPaid ? 'bg-success' : ''}}">
                                            <td {{(!empty($item->note)) ? 'rowspan=2' : ''}} style="vertical-align: middle; width: 150px">{{$item->formatDate()}}</td>
                                            <td>{{$item->formatMoney()}}</td>
                                            <td>{!! $item->formatStatus() !!}</td>
                                            <td style="width: 30px">
                                                <button class="btn btn-xs btn-outline" onclick="MBT_PaymentSchedule.openForm({{$item->id}})">
                                                    <i class="fa fa-pencil"></i> Sửa
                                                </button>
                                            </td>
                                        </tr>
                                        @if(!empty($item->note))
                                            <tr>
                                                <td colspan="3">
                                                    <span style="font-weight: bold; text-decoration: underline; padding-right: 10px;">Ghi chú:</span>
                                                    {!! $item->note !!}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="100%">Chưa có lịch trình thanh toán nào.</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="x_panel">
                    <div class="x_title">
                        <h2>Thêm mới 1 lịch thanh toán cho đơn hàng</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="display-error" class="alert alert-danger hidden"></div>
                        <form id="payment-schedule-form" onsubmit="return;">

                            @include('payment-schedule._form',[
                                'model'=>$paymentSchedule,
                                'order'=>$model,
                                'type'=>\App\PaymentSchedule::DEBT_TYPE
                            ])
                        </form>
                    </div>
                </div>

                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Tổng quan
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>Tổng công nợ</td>
                                    <td class="text-right">{{$model->formatMoney()}}</td>
                                </tr>
                                <tr>
                                    <td>Đã thanh toán</td>
                                    <td class="text-right">{{Common::formatMoney($sum)}}</td>
                                </tr>
                                <tr>
                                    <td>Còn lại</td>
                                    <td class="text-right">{{Common::formatMoney($model->total_money - $sum)}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentSchedule"></div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/payment-schedule.js') }}"></script>
@endsection
