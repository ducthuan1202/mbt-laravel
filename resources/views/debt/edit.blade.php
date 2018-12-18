@extends('layouts.main')

@section('content')

    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            Công Nợ
                            <small>Chỉnh Sửa</small>
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form action="{{ route('debts.update', $model->id) }}" method="POST" onsubmit="return MBT_Debt.onSubmit();">
                            @method('PATCH')
                            @include('debt._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/template/build/js/debt.js') }}"></script>
    <script>
        MBT_Debt.getCustomerByCity();
        // MBT_Debt.getOrderByCustomer();
    </script>
@endsection