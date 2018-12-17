@extends('layouts.main')

@section('content')

    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Đơn hàng
                            <small>Chỉnh Sửa</small>
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form action="{{ route('orders.update', $model->id) }}" method="POST">
                            @method('PATCH')
                            @include('order._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/order.js') }}"></script>
    <script>
        MBT_Order.getCustomerByCity();
        MBT_Order.priceOrAmountOnchange()
    </script>
@endsection