@extends('layouts.main')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel tile">
            <div class="dashboard_graph">
                <div class="row x_title">
                    <div class="col-md-8">
                        <h3>Chăm sóc khách hàng</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input type="text" class="form-control drp-multi" name="date" value="" readonly/>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection
