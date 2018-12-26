@php
/**
* @var $revenueWeek \App\Order[]
* @var $revenueMonth \App\Order[]
*/
@endphp
@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <h1>Báo cáo</h1>

        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2>Doanh thu dự tính</h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        @if($revenueWeek)
                            <div id="reportRevenueThisWeek">

                            </div>
                        @endif
                        <div id="reportRevenueThisMonth"></div>
                    </div>
                </div>

            </div>

            <div class="col-xs-12 col-sm-6">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2>Đơn hàng</h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div id="reportOrder"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/report.js') }}"></script>
    <script>
        MBT_Report.revenueInWeek();
    </script>
@endsection