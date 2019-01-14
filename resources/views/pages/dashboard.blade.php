@php
    use App\Helpers\Common;
/**
* @var $totalMoney integer
* @var $totalMoneyDebt integer
*/

@endphp
@extends('layouts.main')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="well hidden" style="margin-top: 70px">

            <!-- top tiles -->
            <div class="row tile_count">
                <div class="col-md-4 col-sm-4 col-xs-12 tile_stats_count">
                    <span class="count_top"><i class="fa fa-user"></i> Tổng giá trị đơn hàng</span>
                    <div class="count green">{{Common::formatMoney(0)}}</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 tile_stats_count">
                    <span class="count_top"><i class="fa fa-user"></i> Tổng công nợ</span>
                    <div class="count">{{Common::formatMoney(0)}}</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 tile_stats_count">
                    <span class="count_top"><i class="fa fa-clock-o"></i> Tổng tiền thu về</span>
                    <div class="count">{{Common::formatMoney(0)}}</div>
                </div>
            </div>
            <!-- /top tiles -->


        </div>

        <div class="row">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-bullhorn"></i>
                    </div>
                    <div class="count">{{$quotationCount}}</div>

                    <h3>Báo giá</h3>
                    <p><a href="{{route('quotations.index')}}">Xem Danh Sách</a></p>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="count">{{$orderCount}}</div>

                    <h3>Đơn hàng</h3>
                    <p><a href="{{route('orders.index')}}">Xem Danh Sách</a></p>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-users"></i>
                    </div>
                    <div class="count">{{$customerCount}}</div>

                    <h3>Khách Hàng</h3>
                    <p><a href="{{route('customers.index')}}">Xem Danh Sách</a></p>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-phone-square"></i>
                    </div>
                    <div class="count">{{$careCount}}</div>

                    <h3>Lượt CSKH</h3>
                    <p><a href="{{route('cares.index')}}">Xem Danh Sách</a></p>
                </div>
            </div>

            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-map-marker"></i>
                    </div>
                    <div class="count">{{$cityCount}}</div>

                    <h3>Tỉnh Thành</h3>
                    <p><a href="{{route('cities.index')}}">Xem Danh Sách</a></p>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-credit-card"></i>
                    </div>
                    <div class="count">{{$companyCount}}</div>

                    <h3>Công ty</h3>
                    <p><a href="{{route('companies.index')}}">Xem Danh Sách</a></p>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-user"></i>
                    </div>
                    <div class="count">{{$userCount}}</div>

                    <h3>NVKD</h3>
                    <p><a href="{{route('users.index', ['role'=>\App\User::EMPLOYEE_ROLE])}}">Xem Danh Sách</a></p>
                </div>
            </div>

        </div>

        <div class="row hidden">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2>Biểu đồ tỉ lệ khách hàng theo NVKD</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="eChartPieCustomerByUser" style="height:650px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2>Biểu đồ tỉ lệ khách hàng</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="eChartPieCustomerByStatus" style="height:650px;"></div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- /page content -->

@endsection

@section('style')
    <!-- iCheck -->
    <link href="{{ asset('/template/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet"/>
    <!-- bootstrap-progressbar -->
    <link href="{{ asset('/template/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}"
          rel="stylesheet"/>
    <!-- JQVMap -->
    <link href="{{ asset('/template/vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet"/>

@endsection

@section('script')
    <!-- FastClick -->
    <script src="{{ asset('/template/vendors/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('/template/vendors/nprogress/nprogress.js') }}"></script>
    <!-- Chart.js -->
    <script src="{{ asset('/template/vendors/Chart.js/dist/Chart.min.js') }}"></script>
    <!-- gauge.js -->
    <script src="{{ asset('/template/vendors/gauge.js/dist/gauge.min.js') }}"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{ asset('/template/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('/template/vendors/iCheck/icheck.min.js') }}"></script>
    <!-- Skycons -->
    <script src="{{ asset('/template/vendors/skycons/skycons.js') }}"></script>

    <script src="{{ asset('/template/vendors/echarts/dist/echarts.min.js') }}"></script>
    <script src="{{ asset('/template/build/js/dashboard.js') }}"></script>
    <script>
        {{--init_echarts('eChartPieCustomerByUser',{!! $eChartData !!});--}}
        {{--init_echarts('eChartPieCustomerByStatus',{!! $eChartCustomerData !!});--}}
    </script>
@endsection