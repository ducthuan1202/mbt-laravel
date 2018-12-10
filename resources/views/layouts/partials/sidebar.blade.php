
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="/" class="site_title"><i class="fa fa-paw"></i> <span>MBT</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="/template/production/images/img.jpg" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Xin Chào,</span>
                <h2>{{$user->name}}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br/>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li>
                        <a><i class="fa fa-home"></i> Bảng Tin</a>
                    </li>
                    <li>
                        <a href="{{route('cities.index')}}"><i class="fa fa-home"></i> Khu Vực</a>
                    </li>
                    <li>
                        <a href="{{route('companies.index')}}"><i class="fa fa-home"></i> Công Ty</a>
                    </li>
                    <li>
                        <a href="{{route('products.index')}}"><i class="fa fa-home"></i> Sản Phẩm</a>
                    </li>
                    <li>
                        <a href="{{route('skins.index')}}"><i class="fa fa-home"></i> Loại Hình SP</a>
                    </li>
                    <li>
                        <a href="{{route('customers.index')}}"><i class="fa fa-home"></i> Khách Hàng</a>
                    </li>
                    <li>
                        <a href="{{route('cares.index')}}"><i class="fa fa-home"></i> Chăm Sóc KH</a>
                    </li>
                    <li>
                        <a href="{{route('cities.index')}}"><i class="fa fa-home"></i> Báo Giá</a>
                    </li>
                    <li>
                        <a href="{{route('orders.index')}}">
                            <i class="fa fa-home"></i> Đơn Hàng
                            <span class="label label-success pull-right">12</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="menu_section">
                <h3>Live On</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="{{route('users.index')}}"><i class="fa fa-bug"></i> Thành Viên</a>
                    </li>
                    <li>
                        <a href="{{route('roles.index')}}"><i class="fa fa-bug"></i> Nhóm Quyền</a>
                    </li>
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
            <a data-toggle="tooltip" data-placement="top" title="Đăng xuất" href="{{route('logout')}}">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
