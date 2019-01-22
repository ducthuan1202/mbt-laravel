@php
    /**
     * @var $data \App\Order[]
     */
    use App\Helpers\Common;
@endphp

@php $title = 'Công nợ mới'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$title}} ({{ Common::formatNumber($data->total()) }})</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('debt._search')

                @if($message = Session::get('success'))
                    <div class="alert alert-success">{{$message}}</div>
                @endif


                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr class="headings">
                                <th style="vertical-align: middle">No.</th>
                                <th style="vertical-align: middle">Khách hàng</th>
                                <th class="text-right">Tổng nợ</th>
                                <th class="text-right">Đã thanh toán</th>
                                <th class="text-right">Còn lại</th>
                                <th class="text-right">Nhân viên KD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data))
                                @foreach($data as $index => $item)
                                    <tr>
                                        <td>{{ $index + $data->firstItem() }}</td>
                                        <td><b class="text-success">{{$item->formatCustomer()}}</b></td>
                                        <td class="text-right">{{$item->formatTotalMoney()}}</td>
                                        <td class="text-right">{{$item->formatHasPaid()}}</td>
                                        <td class="text-right">{{$item->formatNotPaid()}}</td>
                                        <td class="text-right"><b class="text-success">{{$item->formatUser()}}</b></td>
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

    <div class="modal fade" id="orderModal"></div>
@endsection

@section('script')
    <script>getCitiesAndCustomersByUser();</script>
@endsection