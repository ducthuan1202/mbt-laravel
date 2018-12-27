@php
    /**
     * @var $model \App\Debt
     */
$userId = isset($model->customer) ? $model->customer->user_id : 0;
$cityId = isset($model->customer) ? $model->customer->city_id : 0;
$customerId = old('customer_id') ? old('customer_id') : $model->customer_id;

@endphp

@if(count($errors))
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif

{{csrf_field()}}

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group {{$errors->has('user_id') ? 'has-error' : ''}}">
            <label>Nhân viên kinh doanh</label>
            <select class="form-control chosen-select" name="user_id" id="user_id" onchange="getCitiesAndCustomersByUser()">
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ $user['id'] == $userId || $user['id'] == old('user_id') ? 'selected' : '' }}>{{$user['name']}}</option>
                @endforeach
            </select>
            @if ($errors->has('user_id')) <span class="help-block">{{ $errors->first('user_id') }}</span> @endif
        </div>
        <div class="form-group">
            <label>Khu vực</label>
            <select class="form-control chosen-select" name="city_id" id="city_id" onchange="getCustomerByCityAndUser()">
                <option value="{{$cityId}}">{{$cityId}}</option>
            </select>
        </div>

        <div class="form-group {{$errors->has('customer_id') ? 'has-error' : ''}}">
            <label>Khách hàng</label>
            <select class="form-control chosen-select" name="customer_id" id="customer_id"/>
                <option value="{{$customerId}}">{{$customerId}}</option>
            </select>
            @if ($errors->has('customer_id')) <span class="help-block">{{ $errors->first('customer_id') }}</span> @endif
        </div>

    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group {{$errors->has('total_money') ? 'has-error' : ''}}">
            <label>Số dư nợ (<code>ngàn đồng</code>)</label>
            <input type="text" class="form-control" name="total_money" value="{{old('total_money') ? old('total_money') : $model->total_money}}"/>
            @if ($errors->has('total_money')) <span class="help-block">{{ $errors->first('total_money') }}</span> @endif
        </div>

        <div class="form-group {{$errors->has('date_create') ? 'has-error' : ''}}">
            <label>Ngày tạo công nợ</label>
            <div class="input-group date">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                <input type="text" class="form-control drp-single" name="date_create" value="{{old('date_create') ? old('date_create') : $model->formatDateCreate()}}" readonly/>
                @if ($errors->has('date_create')) <span class="help-block">{{ $errors->first('date_create') }}</span> @endif
            </div>
        </div>

        <div class="form-group {{$errors->has('date_pay') ? 'has-error' : ''}}">
            <label>Ngày (<span class="text-danger">hẹn</span>) thanh toán</label>
            <div class="input-group date">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                <input type="text" class="form-control drp-single" name="date_pay" value="{{old('date_pay') ? old('date_pay') : $model->formatDatePay()}}" readonly/>
                @if ($errors->has('date_pay')) <span class="help-block">{{ $errors->first('date_pay') }}</span> @endif
            </div>
        </div>

    </div>
</div>

<div class="ln_solid"></div>
<div class="form-group">
    @if($model->exists)
        <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
    @else
        <button type="submit" class="btn btn-success">Tạo Mới</button>
    @endif
    <a href="{{route('debts.index')}}" class="btn btn-default">Trở Về</a>
</div>
