@php
    /**
     * @var $data \App\Product[]
     */
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Sản Phẩm
                    <small>Danh sách</small>
                </h2>

                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-round btn-default btn-xs" href="{{route('products.create')}}">
                            <i class="fa fa-plus"></i> Thêm mới
                        </a>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <div role="Search form">
                    @include('product._search')
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
                                <th>Tên Sản Phẩm</th>
                                <th>Thông Số Kỹ Thuật</th>
                                <th>Loại Hình</th>
                                <th>Giá Bán</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data))
                                @foreach($data as $item)
                                    <tr>
                                        <td style="width: 50px">{{$item->id}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>
                                            <span>Tiêu Chuẩn: <code>{{$item->standard}}</code></span><br/>
                                            <span>Hiệu Suất: <code>{{$item->capacity}}</code></span><br/>
                                            <span>Điện Áp Vào: <code>{{$item->voltage_input}}</code></span><br/>
                                            <span>Điện Áp Ra: <code>{{$item->voltage_output}}</code></span>
                                        </td>
                                        <td>{{$item->formatSkin()}}</td>
                                        <td>{{$item->formatMoney()}}</td>
                                        <td style="width: 170px">
                                            <div class="btn-group">
                                                <a class="btn btn-default" href="{{route('products.edit', $item->id)}}">
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
    <!-- FastClick -->
    <script src="{{ asset('/template/build/js/product.js') }}"></script>
@endsection