@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Công Ty
                    <small>Danh sách</small>
                </h2>

                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-round btn-default btn-xs" href="{{route('companies.create')}}">
                            <i class="fa fa-plus"></i> Thêm mới
                        </a>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <div role="Search form">
                    @include('company._search')
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
                                <th>Khu Vực</th>
                                <th>Tên Công Ty</th>
                                <th>Mô Tả</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data))
                                @foreach($data as $item)
                                    <tr>
                                        <td style="width: 50px">{{$item->id}}</td>
                                        <td>{{$item->formatCity()}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->desc}}</td>
                                        <td style="width: 200px">
                                            <div class="btn-group">
                                                <a class="btn btn-default" href="{{route('companies.edit', $item->id)}}">
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
    <script src="{{ asset('/template/build/js/city.js') }}"></script>
@endsection