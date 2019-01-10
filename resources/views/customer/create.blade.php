@php $title = 'Khách hàng'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            {{$title}}
                            <small>Tạo Mới</small>
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form action="{{ route('customers.store') }}" method="POST">
                            @include('customer._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/customer.js') }}"></script>
    <script>MBT_Customer.switchBuyStatus()</script>
@endsection