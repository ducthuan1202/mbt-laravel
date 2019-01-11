@php
    /**
     * @var $model \App\Customer
     * @var $orders \App\Order[]
     * @var $cares \App\Care[]
     * @var $debts \App\Debt[]
     * @var $priceQuotations \App\PriceQuotation[]
     */
@endphp

@php $title = 'Khách hàng'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$title}}: #{{$model->name}}</h2>
                <button onclick="window.history.back()" class="btn btn-default pull-right">Quay lại</button>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <td>Họ tên</td>
                            <td>{{$model->name}}</td>
                        </tr>
                        <tr>
                            <td>Ngày sinh</td>
                            <td>{{$model->formatBirthDay()}}</td>
                        </tr>
                        <tr>
                            <td>Số điện thoại</td>
                            <td>{{$model->mobile}}</td>
                        </tr>
                        <tr>
                            <td>Ngày sinh</td>
                            <td>{{$model->formatBirthDay()}}</td>
                        </tr>
                        <tr>
                            <td>Địa chỉ</td>
                            <td>{{$model->address}}</td>
                        </tr>
                        <tr>
                            <td>Khu vực</td>
                            <td>{{$model->formatCity()}}</td>
                        </tr>
                        <tr>
                            <td>Công ty</td>
                            <td>{{$model->formatCompany()}}</td>
                        </tr>
                        <tr>
                            <td>Mã khách hàng</td>
                            <td>{{$model->code}}</td>
                        </tr>
                        <tr>
                            <td>Nhân viên chăm sóc</td>
                            <td>{{$model->formatUser()}}</td>
                        </tr>
                        <tr>
                            <td>Trạng thái</td>
                            <td>{!! $model->formatStatus() !!}</td>
                        </tr>
                        <tr>
                            <td>Ghi chú</td>
                            <td>{{$model->note}}</td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Lịch sử mua hàng</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div class="table-responsive">
                            @if(count($orders))
                            <table class="table table-striped jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th>Mã ĐH</th>
                                    <th>Ngày vào sản xuất</th>
                                    <th>Giá trị đơn hàng</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $item)
                                        <tr>
                                            <td><b class="text-success">{{$item->code}}</b></td>
                                            <td>{{$item->formatStartDate()}}</td>
                                            <td>{{$item->formatTotalMoney()}}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            @else
                                <div class="alert">Không có dữ liệu</div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Lịch sử chăm sóc
                        </h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <div class="table-responsive">
                            @if(count($cares))
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>Nội dung</th>
                                        <th>Ngày gọi</th>
                                        <th>Ngày hẹn</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cares as $item)
                                        <tr>
                                            <td>{{$item->formatStatus()}}</td>
                                            <td>{{$item->formatStartDate()}}</td>
                                            <td>{{$item->formatEndDate()}}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            @else
                                <div class="alert">Không có dữ liệu</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Lịch sử báo giá
                        </h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <div class="table-responsive">
                            @if(count($priceQuotations))
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>Ngày báo</th>
                                        <th>Kiểu máy</th>
                                        <th>Ngoại hình</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th>Tình trạng</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($priceQuotations as $item)
                                        <tr>
                                            <td>{{$item->formatQuotationDate()}}</td>
                                            <td>{!! $item->formatType() !!}</td>
                                            <td>{!! $item->formatSkin() !!}</td>
                                            <td>{{$item->amount}}</td>
                                            <td>{{$item->formatTotalMoney()}}</td>
                                            <td>{!! $item->formatStatus() !!}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            @else
                                <div class="alert">Không có dữ liệu</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Công nợ
                        </h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <div class="table-responsive">
                            @if(count($debts))
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>Số tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Đơn hàng (nếu có)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($debts as $item)
                                        <tr>
                                            <td>{{$item->formatMoney()}}</td>
                                            <td>{!! $item->formatStatus() !!}</td>
                                            <td>{!! $item->formatOrder() !!}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            @else
                                <div class="alert">Không có dữ liệu</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/customer.js') }}"></script>
@endsection