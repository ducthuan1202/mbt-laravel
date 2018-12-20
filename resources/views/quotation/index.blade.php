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
                    <small>Tổng số <b>{{$data->total()}}</b></small>
                </h2>

                <a class="btn btn-success pull-right" href="{{route('quotations.create')}}">
                    <i class="fa fa-plus"></i> Thêm mới
                </a>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                @include('quotation._search')

                @if($message = Session::get('success'))
                    <div class="alert alert-success">{{$message}}</div>
                @endif

                <div class="ln_solid"></div>

                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr class="headings">
                                <th>No.</th>
                                <th>Ngày báo giá</th>
                                <th>Khách hàng</th>
                                <th>Khu vực</th>
                                <th>Trạng thái</th>
                                <th>Nhân viên KD</th>
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
                                        </td>
                                        <td>
                                            {!! $item->formatCustomerCity() !!}
                                        </td>
                                        <td>{!! $item->formatOrderStatus() !!}</td>
                                        <td>
                                            <b class="text-success">{{$item->formatUser()}}</b>
                                        </td>
                                        <td class="text-right" style="min-width: 150px">
                                            <a onclick="MBT_PriceQuotation.getDetail({{$item->id}})" class="btn btn-primary btn-xs">
                                                <i class="fa fa-folder"></i> Xem
                                            </a>
                                            <a href="{{route('quotations.edit', $item->id)}}" class="btn btn-info btn-xs">
                                                <i class="fa fa-pencil"></i> Sửa
                                            </a>
                                            @can('admin')
                                                <a onclick="MBT_PriceQuotation.delete({{$item->id}})" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-trash-o"></i> Xóa
                                                </a>
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
                    <div role="pagination">{{$data->links()}}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="quotationModal"></div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/quotation.js') }}"></script>
    <script>MBT_PriceQuotation.getCustomerByCityIndex()</script>
@endsection