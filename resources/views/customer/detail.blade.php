
@php
    /**
     * @var $data \App\Customer[]
     */
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    {{$model->name}}
                </h2>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <div class="table-responsive">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Lịch sử mua hàng</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div class="table-responsive">
                            @if(count($orders))
                            <table class="table table-striped jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th>No.</th>
                                    <th>Mã ĐH</th>
                                    <th>Mã ĐH</th>
                                    <th>Mã ĐH</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                        <tr>
                                            <td style="width: 50px">{{$item->id}}</td>
                                            <td>
                                                <b class="text-success">{{$item->name}}</b>
                                                <p style="font-size: 11px">{{$item->code}}</p>
                                            </td>
                                            <td><a class="text-primary" href="tel:{{$item->mobile}}"><b>{{$item->mobile}}</b></a></td>
                                            <td><span class="text-primary">{{$item->position}}</span></td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            @else
                                <div class="alert alert-info">Chưa mua hàng lần nào</div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Lịch sử chăm sóc
                        </h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <div class="table-responsive">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Lịch sử báo giá
                        </h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <div class="table-responsive">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Công nợ
                        </h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <div class="table-responsive">
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/customer.js') }}"></script>
@endsection