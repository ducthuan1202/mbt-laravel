@php
/**
 * @var $data \App\User[]
 */
 $user = auth()->user();
@endphp

@php $title = 'Nhân Sự'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$title}}</h2>

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
                                        @can('admin')

                                            @if((int)$item->role !== \App\User::ADMIN_ROLE)
                                                <a href="{{route('users.edit', $item->id)}}" class="btn btn-info btn-xs">
                                                    <i class="fa fa-pencil"></i> Sửa
                                                </a>

                                                @if((int)$user->role === \App\User::ADMIN_ROLE)
                                                    <a href="{{ route('users.login_as', $item->id) }}" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-sign-in"></i> Login
                                                    </a>
                                                @endif
                                            @endif
                                        @endcan

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
                    <div role="pagination">{{$data->appends($searchParams)->links()}}</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/user.js') }}"></script>
@endsection