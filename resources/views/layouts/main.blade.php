@php
    $user = \Illuminate\Support\Facades\Auth::user();
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/template/production/images/favicon.ico" type="image/ico" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MBT') }}</title>

    <!-- Bootstrap -->
    <link href="{{ asset('/template/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <!-- Font Awesome -->
    <link href="{{ asset('/template/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet"/>
    <!-- NProgress -->
    <link href="{{ asset('/template/vendors/nprogress/nprogress.css') }}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('/template/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet"/>

    <!-- select2 -->
    <link href="{{ asset('/template/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet"/>
    @yield('style')

    <!-- Custom Theme Style -->
    <link href="{{ asset('/template/build/css/custom.min.css') }}" rel="stylesheet"/>
</head>

<body class="nav-md">

<div class="container body">
    <div class="main_container">
        @include('layouts.partials.sidebar')

        <!-- top navigation -->
        @include('layouts.partials.navigation')
        <!-- /top navigation -->

        <!-- page content -->
        @yield('content')
        <!-- /page content -->

        <!-- footer content -->
        @include('layouts.partials.footer')
        <!-- /footer content -->
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('/template/vendors/jquery/dist/jquery.min.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('/template/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{ asset('/template/vendors/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('/template/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- Sweetalert -->
<script src="{{ asset('/template/vendors/sweetalert/sweetalert.min.js') }}"></script>

<script>$.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}});</script>
<script src="{{ asset('/template/vendors/select2/dist/js/select2.min.js') }}"></script>
<!-- general -->
<script src="{{ asset('/template/build/js/general.js') }}"></script>
@yield('script')
<!-- general -->
<script src="{{ asset('/template/build/js/custom.js') }}"></script>
</body>
</html>

