@php
    /**
     * @var $data \App\User[]
     */
     $user = \Illuminate\Support\Facades\Auth::user();
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Nhân Sự
                </h2>

                @can('admin')
                    <a class="btn btn-success pull-right" href="{{route('users.create')}}">
                        <i class="fa fa-plus"></i> Thêm mới
                    </a>
                @endcan

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('user._search')

                @if($message = Session::get('success'))
                    <div class="alert alert-success">{{$message}}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                        <tr class="headings">
                            <th>No.</th>
                            <th>Họ tên</th>
                            <th>Số điện thoại - email</th>
                            <th>Chức danh</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($data))
                            @foreach($data as $key => $item)
                                <tr>
                                    <td style="width: 50px">{{$key + 1}}</td>
                                    <td><b class="text-success">{{$item->name}}</b></td>
                                    <td>
                                        <a href="tel:{{$item->mobile}}"><b>{{$item->mobile}}</b></a>
                                        @if(!empty($item->email))
                                            <br/>
                                            <a href="mailto:{{$item->email}}">
                                                <small>{{$item->email}}</small>
                                            </a>
                                        @endif
                                    </td>
                                    <td>{!! $item->formatRolesText() !!}</td>
                                    <td>{!! $item->formatStatus() !!}</td>
                                    <td class="text-right" style="min-width: 150px">
                                        <a href="{{route('users.edit', $item->id)}}" class="btn btn-info btn-xs">
                                            <i class="fa fa-pencil"></i> Sửa
                                        </a>
                                        @can('admin')
                                            <a onclick="MBT_User.delete({{$item->id}})" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash-o"></i> Xóa
                                            </a>
                                        @endif
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
    <script src="{{ asset('/template/build/js/user.js') }}"></script>
@endsection