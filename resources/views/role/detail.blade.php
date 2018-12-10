@php
    /**
     * @var $model \App\Role
     */
    $prefixRoute = env('PREFIX_BACKEND_ROUTE').'.';
    $prefixViews = env('PREFIX_BACKEND_VIEWS').'.';
@endphp
@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Vai Trò: #'.$model->name) }}
                        <a href="{{route($prefixRoute.'roles')}}" class="btn btn-info btn-sm float-right">Danh Sách Vai Trò</a>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            @if($model)
                                <tr>
                                    <th>ID</th>
                                    <td>{{$model->id}}</td>
                                </tr>
                                <tr>
                                    <th>Tên Vai Trò</th>
                                    <td>{{$model->name}}</td>
                                </tr>
                                <tr>
                                    <th>Mô Tả</th>
                                    <td>{!! $model->text_desc !!}</td>
                                </tr>
                                <tr>
                                    <th>Trạng Thái</th>
                                    <td>{{$model->status}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right">
                                        <a href="{{route($prefixRoute.'role.edit', $model->id)}}"
                                           class="btn btn-warning btn-sm ">Chỉnh Sửa</a>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
