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
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <div>

            <div class="x_panel tile">
                <div class="x_title">
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Settings 1</a>
                                </li>
                                <li><a href="#">Settings 2</a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="container"></div>
                    <div class="clearfix"></div>
                </div>
            </div>


            <script>
                Highcharts.chart('container', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Thống kê báo giá khách hàng'
                    },
                    xAxis: {
                        categories: [ '{{$t6['date']}}', '{{$t5['date']}}', '{{$t4['date']}}', '{{$t3['date']}}', '{{$t2['date']}}', '{{$t1['date']}}', '{{$today['date']}}']
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Số lượt'
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                            }
                        }
                    },
                    legend: {
                        align: 'right',
                        x: -30,
                        verticalAlign: 'top',
                        y: 25,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        headerFormat: '<b>{point.x}</b><br/>',
                        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                            }
                        }
                    },
                    series: [{
                        name: 'Đang theo',
                        data: [
                            @php  if(isset($t6['data'][2]->count)) echo $t6['data'][2]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t5['data'][2]->count)) echo $t5['data'][2]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t4['data'][2]->count)) echo $t4['data'][2]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t3['data'][2]->count)) echo $t3['data'][2]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t2['data'][2]->count)) echo $t2['data'][2]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t1['data'][2]->count)) echo $t1['data'][2]->count; else{echo 0;} @endphp ,
                            @php  if(isset($today['data'][2]->count)) echo $today['data'][2]->count; else{echo 0;} @endphp]
                    }, {
                        name: 'Thất bại',
                        data: [
                            @php  if(isset($t6['data'][1]->count)) echo $t6['data'][1]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t5['data'][1]->count)) echo $t5['data'][1]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t4['data'][1]->count)) echo $t4['data'][1]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t3['data'][1]->count)) echo $t3['data'][1]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t2['data'][1]->count)) echo $t2['data'][1]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t1['data'][1]->count)) echo $t1['data'][1]->count; else{echo 0;} @endphp ,
                            @php  if(isset($today['data'][1]->count)) echo $today['data'][1]->count; else{echo 0;} @endphp]
                    }, {
                        name: 'Thành công',
                        {{--data: [5, 5, 5, 3, 4, 7, @if isset($today['data'][0]['count']){{$today['data'][0]['count']}}  @else 0 @endif]--}}
                        data: [
                            @php  if(isset($t6['data'][0]->count)) echo $t6['data'][0]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t5['data'][0]->count)) echo $t5['data'][0]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t4['data'][0]->count)) echo $t4['data'][0]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t3['data'][0]->count)) echo $t3['data'][0]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t2['data'][0]->count)) echo $t2['data'][0]->count; else{echo 0;} @endphp ,
                            @php  if(isset($t1['data'][0]->count)) echo $t1['data'][0]->count; else{echo 0;} @endphp ,
                            @php  if(isset($today['data'][0]->count)) echo $today['data'][0]->count; else{echo 0;} @endphp]
                    }]
                });
            </script>

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