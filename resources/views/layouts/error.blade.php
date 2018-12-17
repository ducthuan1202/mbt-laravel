<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Trang lỗi</title>

    <link href="{{ asset('/template/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <!-- Custom Theme Style -->
    <link href="{{ asset('/template/build/css/custom.min.css') }}" rel="stylesheet"/>
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        @yield('content')
    </div>
</div>
</body>
</html>
