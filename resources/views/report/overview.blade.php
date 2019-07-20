@php
    use \App\Helpers\Common;
    /**
    * @var $date string
    */
@endphp
@php $title = 'Báo Cáo'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">

        <div class="x_panel tile">
            <div class="x_title">
                <h2><i class="fa fa-bar-chart-o"></i> Báo cáo tổng quan:
                    <small>{{$date}}</small>
                </h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="well" style="overflow: auto">
                    <form class="form" action="/report" method="GET">
                        <div class="row">

                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                                <div class="form-group">
                                    <label>Nhân viên</label>

                                    @can('admin')
                                        <select class="form-control chosen-select" name="user" id="user_id">
                                            <option value="0">Tất cả nhân viên KD</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user['id'] }}" {{ $user['id'] == $uid ? 'selected' : '' }}>{{$user['name']}}</option>
                                            @endforeach
                                        </select>
                                    @elsecan('employee')
                                        @php $userLogin = \Illuminate\Support\Facades\Auth::user(); @endphp
                                        <select class="form-control chosen-select" name="user" id="user_id">
                                            <option value="{{ $userLogin->id}}" selected>{{$userLogin->name}}</option>
                                        </select>
                                    @endcan

                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                                <div class="form-group">
                                    <label>Chọn thời gian</label>
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-calendar"></i></span>
                                        <input type="text" class="form-control drp-multi" name="date" value="{{$date}}"
                                               readonly/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-2">
                                <div class="form-group" style="margin-top: 24px;">
                                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <div class="table-responsive">
                    <table class="table jambo_table bulk_action">
                        <thead>
                        <tr class="headings">
                            <th>Nội dung thống kê</th>
                            <th>Số lượng</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Tổng số khách hàng mới</td>
                            <td>{{$customersData}}</td>
                            <td class="text-right">
                                <a href="{{route('report.customers', ['date'=>$date])}}" class="btn btn-xs btn-success">
                                    <i class="fa fa-folder"></i> Xem thống kê chi tiết
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Tổng số lượt CSKH</td>
                            <td>{{$caresDate}}</td>
                            <td class="text-right"><a href="{{route('report.cares', ['date'=>$date])}}"
                                                      class="btn btn-xs btn-success"><i class="fa fa-folder"></i> Xem
                                    thống kê chi tiết</a></td>
                        </tr>
                        <tr>
                            <td>Tổng số lượt báo giá</td>
                            <td>{{$quotationsData}}</td>
                            <td class="text-right"><a href="{{route('report.quotations', ['date'=>$date])}}"
                                                      class="btn btn-xs btn-success"><i class="fa fa-folder"></i> Xem
                                    thống kê chi tiết</a></td>
                        </tr>
                        <tr>
                            <td>Tổng số đơn hàng</td>
                            <td>{{$ordersData}}</td>
                            <td class="text-right"><a href="{{route('report.orders', ['date'=>$date])}}"
                                                      class="btn btn-xs btn-success"><i class="fa fa-folder"></i> Xem
                                    thống kê chi tiết</a></td>
                        </tr>
                        <tr>
                            <td>Tổng số công nợ mới</td>
                            <td>{{$debtsData}} ({{\App\Helpers\Common::formatMoney($sumOldDebt->total)}})</td>
                            <td class="text-right"><a href="" class="btn btn-xs btn-success"><i
                                            class="fa fa-folder"></i> Xem thống kê chi tiết</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <script src="https://code.highcharts.com/highcharts.js"></script>
        <div class="x_panel tile">
            <div class="x_title">
                <h2><i class="fa fa-bar-chart-o"></i> Biểu đồ báo cáo</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div id="container1"></div>
                <script>
                    Highcharts.chart('container1', {
                        chart: {
                            type: 'area'
                        },
                        title: {
                            text: 'Biểu đồ số lượng báo giá theo ngày'
                        },
                        subtitle: {
                            text: ' '
                        },
                        xAxis: {
                            categories: [ @foreach($db1 as $index => $item)
                                '{{$item->date}}'
                                @if($index + 1 < count($db1))
                                ,
                                @endif
                            @endforeach
                            ],
                            tickmarkPlacement: 'on',
                            title: {
                                enabled: false
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Billions'
                            },
                            labels: {
                                formatter: function () {
                                    return this.value / 1;
                                }
                            }
                        },
                        tooltip: {
                            split: true,
                            valueSuffix: ' báo giá'
                        },
                        plotOptions: {
                            area: {
                                stacking: 'normal',
                                lineColor: '#666666',
                                lineWidth: 1,
                                marker: {
                                    lineWidth: 1,
                                    lineColor: '#666666'
                                }
                            }
                        },
                        series: [{
                            name: ' ',
                            data: [@foreach($db1 as $index => $item)
                                {{$item->count}}
                                @if($index + 1 < count($db1))
                                ,
                                @endif
                                @endforeach
                            ]
                        }]
                    });
                </script>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <div class="x_title">
                        <h2>Biểu đồ tỷ lệ số báo giá theo trạng thái</h2>
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
                        <div id="container2"></div>
                        <script>
                            // Build the chart
                            Highcharts.setOptions({
                                colors: ['#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
                            });
                            Highcharts.chart('container2', {
                                chart: {
                                    plotBackgroundColor: null,
                                    plotBorderWidth: null,
                                    plotShadow: false,
                                    type: 'pie'
                                },
                                title: {
                                    text: ''
                                },
                                tooltip: {
                                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                },
                                plotOptions: {
                                    pie: {
                                        allowPointSelect: true,
                                        cursor: 'pointer',
                                        dataLabels: {
                                            enabled: false
                                        },
                                        showInLegend: true
                                    }
                                },
                                series: [{
                                    name: 'Brands',
                                    colorByPoint: true,
                                    data: [
                                            @foreach($db2 as $index => $item)
                                        {
                                            name: '{{$item->statusText}}',
                                            y: {{$item->count}}

                                        }
                                        @if($index + 1 < count($db2))
                                        ,
                                        @endif
                                        @endforeach
                                    ]
                                }]
                            });

                        </script>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <div class="x_title">
                        <h2>Biểu đồ tỷ lệ số báo giá theo nhân viên</h2>
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
                        <div id="container4"></div>
                        <script>Highcharts.chart('container4', {
                                chart: {
                                    plotBackgroundColor: null,
                                    plotBorderWidth: null,
                                    plotShadow: false,
                                    type: 'pie'
                                },
                                title: {
                                    text: ''
                                },
                                tooltip: {
                                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                },
                                plotOptions: {
                                    pie: {
                                        allowPointSelect: true,
                                        cursor: 'pointer',
                                        dataLabels: {
                                            enabled: true,
                                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                            style: {
                                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                            }
                                        }
                                    }
                                },
                                series: [{
                                    name: 'Brands',
                                    colorByPoint: true,
                                    data: [
                                            @foreach($db3 as $index => $item)
                                        {
                                            name: '{{$item->name}}',
                                            y: {{$item->count}}
                                        }
                                        @if($index + 1 < count($db3))
                                        ,
                                        @endif
                                        @endforeach


                                    ]
                                }]
                            });</script>


                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <div class="x_title">
                        <h2>Số tiền báo giá</h2>
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
                        <div id="container5"></div>
                        <script>
                            Highcharts.chart('container5', {
                                chart: {
                                    type: 'line'
                                },
                                title: {
                                    text: 'Biểu đồ số tiền báo giá'
                                },
                                subtitle: {
                                    text: ' '
                                },
                                xAxis: {
                                    categories: [@foreach($db5 as $index => $item)
                                        '{{$item->date}}'
                                        @if($index + 1 < count($db5))
                                        ,
                                        @endif
                                        @endforeach]
                                },
                                yAxis: {
                                    title: {
                                        text: ' '
                                    }
                                },
                                plotOptions: {
                                    line: {
                                        dataLabels: {
                                            enabled: true
                                        },
                                        enableMouseTracking: false
                                    }
                                },
                                series: [{
                                    name: 'Tokyo',
                                    data: [@foreach($db5 as $index => $item)
                                        {{$item->total*1000}}
                                        @if($index + 1 < count($db5))
                                        ,
                                        @endif
                                        @endforeach]
                                }]
                            });
                        </script>


                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <div class="x_title">
                        <h2>Tỷ lệ báo giá theo tổng tiền</h2>
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
                        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                        <script type="text/javascript">
                            google.charts.load("current", {packages:["corechart"]});
                            google.charts.setOnLoadCallback(drawChart);
                            function drawChart() {
                                var data = google.visualization.arrayToDataTable([
                                    ['Task', 'Hours per Day'],
                                        @foreach($db6 as $index => $item)

                                            ['{{$item->statusText}}',     {{$item->total*1000}}]

                                    @if($index + 1 < count($db6))
                                    ,
                                        @endif
                                        @endforeach

                                ]);

                                var options = {
                                    title: 'Số tiền báo giá',
                                    is3D: true,
                                    sliceVisibilityThreshold: .001
                                };

                                var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                                chart.draw(data, options);
                            }
                        </script>
                        <div id="piechart_3d" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <div class="x_title">
                        <h2>Số tiền báo giá theo nhân viên</h2>
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
                        <script type="text/javascript">
                            google.charts.load('current', {'packages':['corechart']});
                            google.charts.setOnLoadCallback(drawChart);

                            function drawChart() {

                                var data = new google.visualization.DataTable();
                                data.addColumn('string', 'Pizza');
                                data.addColumn('number', 'Populartiy');
                                data.addRows([
                                        @foreach($db7 as $index => $item)

                                    ['{{$item->name}}', {{$item->total}}]

                                    @if($index + 1 < count($db7))
                                    ,
                                        @endif
                                        @endforeach

                                ]);

                                var options = {
                                    title: ' ',
                                    sliceVisibilityThreshold: .001
                                };

                                var chart = new google.visualization.PieChart(document.getElementById('piechart_7'));
                                chart.draw(data, options);
                            }
                        </script>
                        <div id="piechart_7" style="width: 100%; height: 300px;"></div>



                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
