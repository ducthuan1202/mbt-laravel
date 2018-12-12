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
                    <small>Danh sách</small>
                </h2>

                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-round btn-default btn-xs" href="{{route('users.create')}}">
                            <i class="fa fa-plus"></i> Thêm mới
                        </a>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <div role="Search form">
                    @include('user._search')
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
                                <th>Số Điện Thoại</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Nhóm Quyền</th>
                                <th>Trạng Thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data))
                                @foreach($data as $key => $item)
                                    <tr>
                                        <td style="width: 50px">{{$key + 1}}</td>
                                        <td>{{$item->mobile}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->email}}</td>
                                        <td>{!! $item->formatRole() !!}</td>
                                        <td>{!! $item->formatStatus() !!}</td>
                                        <td style="width: 160px">

                                            <div class="btn-group">
                                                <a class="btn btn-default" href="{{route('users.edit', $item->id)}}">
                                                    <i class="fa fa-edit"></i> Sửa
                                                </a>

                                                @if($user->id !== $item->id)
                                                    <a class="btn btn-default" onclick="MBT_User.delete({{$item->id}})">
                                                        <i class="fa fa-trash"></i> Xóa
                                                    </a>
                                                @else
                                                    <a class="btn btn-dark" onclick="alertError({text: 'Bạn không thể thực hiện thao tác này.'})">
                                                        <i class="fa fa-trash"></i> Xóa
                                                    </a>
                                                @endif
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
    <script src="{{ asset('/template/build/js/user.js') }}"></script>
@endsection