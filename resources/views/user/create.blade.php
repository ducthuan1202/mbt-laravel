@php $title = 'Nhân Sự'; @endphp
@extends('layouts.main')
@section('title') {{$title}} @endsection

@section('content')

    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            {{$title}}
                            <small>Tạo Mới</small>
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form action="{{ route('users.store') }}" method="POST">
                            @include('user._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
