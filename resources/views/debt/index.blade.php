@php
    /**
     * @var $data \App\Debt[]
     */
@endphp

@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Công Nợ
                    <small>Danh sách</small>
                </h2>

                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-round btn-default btn-xs" href="{{route('debts.create')}}">
                            <i class="fa fa-plus"></i> Thêm mới
                        </a>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <div role="Search form">
                    @include('debt._search')
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
                                <th>Khách Hàng</th>
                                <th>NVKD</th>
                                <th>Ngày Hẹn</th>
                                <th>Số Lượng</th>
                                <th>Giá</th>
                                <th>VAT</th>
                                <th>Số Dư</th>
                                <th>Trạng Thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data))
                                @foreach($data as $item)
                                    <tr>
                                        <td style="width: 50px">{{$item->id}}</td>
                                        <td>{{$item->formatCustomer()}}</td>
                                        <td>{{$item->formatUser()}}</td>
                                        <td>{{$item->formatDebtDate()}}</td>
                                        <td>{{$item->amount}}</td>
                                        <td>{{$item->price}}</td>
                                        <td>{{$item->vat}}</td>
                                        <td>{{$item->residual}}</td>
                                        <td>{!! $item->formatStatus() !!}</td>

                                        <td style="width: 170px">
                                            <div class="btn-group">
                                                <a class="btn btn-default" href="{{route('debts.edit', $item->id)}}">
                                                    <i class="fa fa-edit"></i> Sửa
                                                </a>
                                                <a class="btn btn-default" onclick="MBT_Debt.delete({{$item->id}})">
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
    <script src="{{ asset('/template/build/js/debt.js') }}"></script>
@endsection