@php
    /**
     * @var $data \App\PriceQuotation[]
     */
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Báo Giá
                    <small>Danh sách</small>
                </h2>

                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-round btn-default btn-xs" href="{{route('quotations.create')}}">
                            <i class="fa fa-plus"></i> Thêm mới
                        </a>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <div role="Search form">
                    @include('quotation._search')
                </div>

                @if($message = Session::get('success'))
                    <div class="alert alert-success">{{$message}}</div>
                @endif

                <div class="ln_solid"></div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr class="headings">
                                <th>STT</th>
                                <th>Ngày Báo Giá</th>
                                <th>Khách Hàng</th>
                                <th>Sản Phẩm</th>
                                <th>Giá Báo</th>
                                <th>NVKD</th>
                                <th>Chi Tiết</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data))
                                @foreach($data as $item)
                                    <tr>
                                        <td style="width: 50px">{{$item->id}}</td>
                                        <td>{{$item->formatQuotationDate()}}</td>
                                        <td>
                                            <span class="text-success">{!! $item->formatCustomer() !!}</span>
                                            <p>{!! $item->formatCustomerStatus() !!}</p>
                                        </td>
                                        <td>{{$item->formatProduct()}}</td>
                                        <td>{{$item->formatMoney()}}</td>
                                        <td>
                                            <p>Lắp đạt tại: {{$item->setup_at}}</p>
                                            <p>Giao hàng tại: {{$item->delivery_at}}</p>
                                            <p>Bảo hành: {{$item->guarantee}} (tháng)</p>
                                        </td>
                                        <td>
                                            <b class="text-success">{{$item->formatCustomerUser()}}</b>
                                        </td>
                                        <td style="width: 170px">
                                            <div class="btn-group">
                                                <a class="btn btn-default" href="{{route('quotations.edit', $item->id)}}">
                                                    <i class="fa fa-edit"></i> Sửa
                                                </a>
                                                <a class="btn btn-default" onclick="MBT_Company.delete({{$item->id}})">
                                                    <i class="fa fa-trash"></i> Xóa
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="100%">Không có dữ liệu.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if(count($data))
                    <div role="pagination">{{$data->links()}}</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/quotation.js') }}"></script>
@endsection