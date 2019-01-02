@php
    use \App\Helpers\Common;
    /**
    * @var $users \App\User[]
    */
@endphp
@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2><i class="fa fa-slideshare"></i> Báo giá: <small>{{$date}}</small></h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        @if($users && count($users))
                            <div class="table-responsive">
                                <table class="table jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>No.</th>
                                        <th>Nhân viên</th>
                                        <th>Số lượt báo giá</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $index => $user)
                                        <tr>
                                            <td>{{$index + 1}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{count($user->quotations)}}</td>
                                            <td class="text-right">
                                                <a href="{{route('report.quotations_detail', ['date'=>$date, 'userId'=>$user->id ])}}"
                                                   class="btn btn-xs btn-primary">
                                                    <i class="fa fa-folder"></i>
                                                    Xem chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
