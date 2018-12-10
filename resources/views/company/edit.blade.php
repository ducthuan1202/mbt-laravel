@extends('layouts.main')

@section('content')

    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Công Ty
                            <small>Chỉnh Sửa</small>
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form action="{{ route('companies.update', $model->id) }}" method="POST">
                            @method('PATCH')
                            @include('company._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
