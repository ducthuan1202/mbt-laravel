@php
    /**
     * @var $data \App\Care[]
     */
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Chăm Sóc Khách Hàng
                    <small>Danh sách</small>
                </h2>

                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-round btn-default btn-xs" href="{{route('cares.create')}}">
                            <i class="fa fa-plus"></i> Thêm mới
                        </a>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <div role="Search form">
                    @include('care._search')
                </div>

                @if($message = Session::get('success'))
                    <div role="show errors">
                        <div class="alert alert-success">{{$message}}</div>
                    </div>
                @endif

                <div class="ln_solid"></div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr class="headings">
                            <th>STT</th>
                            <th>Ngày Chăm Sóc</th>
                            <th>Nhân Viên</th>
                            <th>Khách Hàng</th>
                            <th>Nội Dung</th>
                            <th>Trạng Thái</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($data))
                            @foreach($data as $item)
                                <tr>
                                    <td style="width: 50px">{{$item->id}}</td>
                                    <td>{{$item->formatDate()}}</td>
                                    <td>{{$item->formatUser()}}</td>
                                    <td>{{$item->formatCustomer()}}</td>
                                    <td>{{$item->content}}</td>
                                    <td>{!! $item->formatStatus() !!}</td>
                                    <td style="width: 220px">
                                        <div class="btn-group btn-group-sm">
                                            <a class="btn btn-default" onclick="alert(1)">
                                                <i class="fa fa-eye"></i> Xem
                                            </a>
                                            <a class="btn btn-default" href="{{route('cares.edit', $item->id)}}">
                                                <i class="fa fa-edit"></i> Sửa
                                            </a>
                                            <a class="btn btn-default" onclick="MBT_Customer.delete({{$item->id}})">
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
    <script src="{{ asset('/template/build/js/care.js') }}"></script>
@endsection