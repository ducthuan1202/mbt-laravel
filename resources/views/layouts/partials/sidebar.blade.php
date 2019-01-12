@php
use \App\Helpers\Common;

$dateRangeThisWeek = Common::getDateRangeOfThisWeek();
$dateRangeThisMonth = Common::getDateRangeOfThisMonth();
$dateRangeNextWeek = Common::getDateRangeOfNextWeek();
$dateRangeNextMonth = Common::getDateRangeOfNextMonth();
@endphp
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="/" class="site_title"><i class="fa fa-paw"></i> <span>MBT</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="/template/production/images/picture.jpg" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <p class="text-center" style="font-size: 11px">{!! $user ? $user->formatRolesText() : '' !!}</p>
                <h2>{{$user ? $user->name : ''}}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br/>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                {{--<h3>DANH SÁCH MENU CHÍNH</h3>--}}
                <ul class="nav side-menu">
                    <li>
                        <a href="{{route('home')}}"><i class="fa fa-home"></i> Bảng Tin</a>
                    </li>
                    <li>
                        <a href="{{route('cities.index')}}"><i class="fa fa-map-marker"></i> Khu Vực</a>
                    </li>
                    <li>
                        <a href="{{route('companies.index')}}"><i class="fa fa-credit-card"></i> Công Ty</a>
                    </li>
                    @can('admin')
                        <li>
                            <a href="{{route('users.index')}}"><i class="fa fa-user"></i> Nhân Sự</a>
                        </li>
                    @endcan
                    <li>
                        <a href="{{route('customers.index')}}"><i class="fa fa-users"></i> Khách Hàng</a>
                    </li>
                    <li>
                        <a href="{{route('cares.index')}}"><i class="fa fa-phone-square"></i> CSKH</a>
                    </li>
                    <li>
                        <a><i class="fa fa-bullhorn"></i> Báo Giá <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('quotations.index')}}">Tất cả</a></li>
                            <li><a href="{{route('quotations.index', ['status'=>\App\PriceQuotation::SUCCESS_STATUS])}}">Thành công</a></li>
                            <li><a href="{{route('quotations.index', ['status'=>\App\PriceQuotation::PENDING_STATUS])}}">Đang theo</a></li>
                            <li><a href="{{route('quotations.index', ['status'=>\App\PriceQuotation::FAIL_STATUS])}}">Thất bại</a></li>
                        </ul>
                    </li>

                    @can('admin')
                        <li>
                            <a><i class="fa fa-shopping-cart"></i> Đơn Hàng <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="{{route('orders.index')}}">Tất cả</a></li>
                                <li><a href="{{route('orders.index', ['status'=>\App\Order::SHIPPED_STATUS])}}">Đã giao</a></li>
                                <li><a href="{{route('orders.index', ['status'=>\App\Order::NOT_SHIPPED_STATUS])}}">Chưa giao</a></li>
                                <li><a href="{{route('orders.index', ['status'=>\App\Order::CANCEL_STATUS])}}">Đã hủy</a></li>
                            </ul>
                        </li>
                        <li class="{{Request::is('debts') || Request::is('debts/*')  ? 'active' : ''}}">
                            <a><i class="fa fa-slideshare"></i> Công Nợ <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu" style="{{Request::is('debts') || Request::is('debts/*')  ? 'display:block' : ''}}">
                                <li>
                                    <a href="{{route('debts.index', ['status'=>\App\Debt::OLD_STATUS])}}">Công nợ cũ</a>
                                </li>
                                <li>
                                    <a href="{{route('debts.index', ['status'=>\App\Debt::NEW_STATUS])}}">Công nợ mới</a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                </ul>
            </div>

            <div class="menu_section">

                <ul class="nav side-menu">
                    @can('admin')
                        <li>
                            <a><i class="fa fa-bar-chart-o"></i> Báo cáo <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="{{route('report.overview', ['date'=>$dateRangeThisWeek])}}">Tuần này</a></li>
                                <li><a href="{{route('report.overview', ['date'=>$dateRangeThisMonth])}}">Tháng này</a></li>
                            </ul>
                        </li>

                        <li>
                            <a><i class="fa fa-bar-chart-o"></i> Doanh thu<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="{{route('report.money_present', ['date'=>$dateRangeThisWeek])}}">Tuần này</a></li>
                                <li><a href="{{route('report.money_present', ['date'=>$dateRangeThisMonth])}}">Tháng này</a></li>

                                <li><a href="{{route('report.money_future', ['date'=>$dateRangeNextWeek])}}">Tuần tới</a></li>
                                <li><a href="{{route('report.money_future', ['date'=>$dateRangeNextMonth])}}">Tháng tới</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="{{route('cronjob.ssps')}}"><i class="fa fa-refresh"></i> Cập nhật lịch thanh toán</a>
                        </li>
                    @endcan
                </ul>
            </div>

        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings" onclick="alertSuccess({title: 'Xin chào', text: 'Cài đặt đang cập nhật'})">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen" onclick="alertSuccess({title: 'Xin chào', text: 'Xem toàn màn hình đang cập nhật'})">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock" onclick="alertSuccess({title: 'Xin chào', text: 'Khóa màn hình đang cập nhật'})">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Đăng xuất" href="{{route('logout')}}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
