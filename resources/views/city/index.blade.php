@php
    /**
     * @var $data \App\City[]
     */
@endphp
@php $title = 'Khu vực'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$title}}</h2>
                <a class="btn btn-success pull-right" href="{{route('cities.create')}}">
                    <i class="fa fa-plus"></i> Thêm mới
                </a>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('city._search')

                @if($message = Session::get('success'))
                    <div class="alert alert-success">{{$message}}</div>
                @endif

                <div class="ln_solid"></div>

                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                        <tr class="headings">
                            <th>No.</th>
                            <th>Tên khu vực (tỉnh)</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($data))
                            @foreach($data as $index => $item)
                                <tr>
                                    <td>{{ $index + $data->firstItem() }}</td>
                                    <td>{{$item->name}}</td>
                                    <td class="text-right" style="min-width: 150px">
                                        <a href="{{route('cities.edit', $item->id)}}" class="btn btn-info btn-xs">
                                            <i class="fa fa-pencil"></i> Sửa
                                        </a>
                                        @can('admin')
                                            {{--<a onclick="MBT_City.delete({{$item->id}})" class="btn btn-danger btn-xs">--}}
                                                {{--<i class="fa fa-trash-o"></i> Xóa--}}
                                            {{--</a>--}}
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
    <!-- FastClick -->
    <script src="{{ asset('/template/build/js/city.js') }}"></script>
@endsection