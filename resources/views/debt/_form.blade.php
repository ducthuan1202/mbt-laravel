@php
    /**
     * @var $model \App\Debt
     */
$userId = isset($model->customer) ? $model->customer->user_id : 0;
$cityId = isset($model->customer) ? $model->customer->city_id : 0;

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
    <div class="col-xs-12">

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Nhân viên kinh doanh</label>
                    <select class="form-control chosen-select" name="user_id" id="user_id" onchange="MBT_Debt.getCustomerByCity()">
                        @foreach($users as $user)
                            <option value="{{ $user['id'] }}" {{ $user['id'] == $userId || $user['id'] == old('user_id') ? 'selected' : '' }}>{{$user['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" name="city_id" id="city_id" onchange="MBT_Debt.getCustomerByCity()">
                        @foreach($cities as $city)
                            <option value="{{ $city['id'] }}"
                                    {{ $city['id'] == $cityId || $city['id'] == old('city_id') ? 'selected' : '' }}>
                                {{$city['name']}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Khách hàng</label>
                    <select class="form-control chosen-select" name="customer_id" id="customer_id" onchange="MBT_Debt.getOrderByCustomer()">
                        <option value="{{$model->customer_id}}">{{$model->customer_id}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Đơn hàng</label>
                    <select class="form-control chosen-select" name="order_id" id="order_id">
                        <option value="{{$model->order_id}}">đang tải dữ liệu</option>
                    </select>
                    <span class="help-block">Nếu là nợ cũ thì không cần chọn đơn hàng</span>
                </div>

            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Số dư nợ</label>
                    <input type="text" class="form-control" name="total_money" value="{{old('total_money') ? old('total_money') : $model->total_money}}"/>
                    <span class="help-block">tỉ lệ 1:1000 (<code>1 = 1,000 VNĐ></code>)</span>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Kiểu công nợ</label>
                    <select class="form-control" name="status" id="status">
                        @foreach($model->listStatus() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->status || $key == old('status')) ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Tình trạng công nợ</label>
                    <select class="form-control" name="type" id="type">
                        @foreach($model->listType() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->type || $key == old('type')) ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Ngày tạo công nợ</label>
                    <div class="input-group date">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <input type="text" class="form-control drp-single" name="date_create"
                               value="{{old('date_create') ? old('date_create') : $model->formatDateCreate()}}" readonly/>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Ngày thanh toán</label>
                    <div class="input-group date">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <input type="text" class="form-control drp-single" name="date_pay"
                               value="{{old('date_pay') ? old('date_pay') : $model->formatDatePay()}}" readonly/>
                    </div>
                </div>
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
