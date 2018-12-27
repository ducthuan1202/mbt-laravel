@php
    /**
     * @var $model \App\Care
     */
$userLogin = \Illuminate\Support\Facades\Auth::user();
$cityId = old('city_id') ? old('city_id') : 0;
$customerId = old('customer_id') ? old('customer_id') : ($model->customer_id ? $model->customer_id : 0);
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
    <div class="col-md-6">
        <div class="form-group {{$errors->has('user_id') ? 'has-error' : ''}}">
            <label>Nhân viên kinh doanh</label>

            @can('admin')
                <select class="form-control chosen-select" name="user_id" id="user_id" onchange="getCitiesByUser()">
                    @foreach($users as $user)
                        <option value="{{ $user['id'] }}" {{ $user['id'] == $model->user_id || $user['id'] == old('user_id') ? 'selected' : '' }}>{{$user['name']}}</option>
                    @endforeach
                </select>
            @elsecan('employee')
                <select class="form-control chosen-select" name="user_id" id="user_id" onchange="getCitiesByUser()">
                    <option value="{{ $userLogin->id}}" selected>{{$userLogin->name}}</option>
                </select>
            @endcan
            @if ($errors->has('user_id')) <span class="help-block">{{ $errors->first('user_id') }}</span> @endif
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" name="city_id" onchange="getCustomerByCityAndUser()" id="city_id">
                        <option value="{{$cityId}}">đang tải dữ liệu</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group {{$errors->has('customer_id') ? 'has-error' : ''}}">
                    <label>Khách hàng</label>
                    <select class="form-control chosen-select" name="customer_id" id="customer_id">
                        <option value="{{$customerId}}">đang tải dữ liệu</option>
                    </select>
                    @if ($errors->has('customer_id')) <span class="help-block">{{ $errors->first('customer_id') }}</span> @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group {{$errors->has('start_date') ? 'has-error' : ''}}">
                    <label>Ngày gọi</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="text" class="form-control drp-single" name="start_date" value="{{old('start_date') ? old('start_date') : $model->formatStartDate()}}" readonly/>
                    </div>
                    @if ($errors->has('start_date')) <span class="help-block">{{ $errors->first('start_date') }}</span> @endif
                </div>

            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group {{$errors->has('end_date') ? 'has-error' : ''}}">
                    <label>Ngày hẹn gọi lại</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="text" class="form-control drp-single" name="end_date" value="{{old('end_date') ? old('end_date') : $model->formatEndDate()}}" readonly/>
                    </div>
                    @if ($errors->has('end_date')) <span class="help-block">{{ $errors->first('end_date') }}</span> @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">

        <div class="form-group {{$errors->has('status') ? 'has-error' : ''}}">
            <label>Nội dung chăm sóc</label>
            <select class="form-control chosen-select" name="status">
                @foreach($status as $key => $val)
                    <option value="{{ $key }}" {{ $key == $model->status || $key == old('status') ? 'selected' : '' }}>{{$val}}</option>
                @endforeach
            </select>
            @if ($errors->has('status')) <span class="help-block">{{ $errors->first('status') }}</span> @endif
        </div>

        <div class="form-group {{$errors->has('customer_note') ? 'has-error' : ''}}">
            <label>Mô tả khách hàng</label>
            <textarea class="form-control" name="customer_note" rows="5">{{$model->customer_note}}</textarea>
            @if ($errors->has('customer_note')) <span class="help-block">{{ $errors->first('customer_note') }}</span> @endif
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
    <a href="{{route('cares.index')}}" class="btn btn-default">Trở Về</a>
</div>
