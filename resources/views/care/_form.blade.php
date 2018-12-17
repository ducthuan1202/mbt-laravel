@php
    /**
     * @var $model \App\Care
     */
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
        <div class="form-group">
            <label>Nhân viên kinh doanh</label>
            <select class="form-control chosen-select" name="user_id" id="user_id" onchange="MBT_Care.getCustomerByCity()">
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ $user['id'] == $model->user_id || $user['id'] == old('user_id') ? 'selected' : '' }}>{{$user['name']}}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" onchange="MBT_Care.getCustomerByCity()" id="city_id">
                        @foreach($cities as $city)
                            <option value="{{ $city['id'] }}"
                                    {{ isset($model->customer) && $city['id'] == $model->customer->city_id ? 'selected' : '' }}>
                                {{$city['name']}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Khách hàng</label>
                    <select class="form-control chosen-select" name="customer_id" id="customer_id">
                        <option>đang tải dữ liệu...</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Ngày gọi</label>
                    <div class="input-group date">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <input type="text" class="form-control drp-single" name="start_date"
                               value="{{old('start_date') ? old('start_date') : $model->formatStartDate()}}" readonly/>
                    </div>
                </div>

            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Ngày hẹn gọi lại</label>
                    <div class="input-group date">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <input type="text" class="form-control drp-single" name="end_date"
                               value="{{old('end_date') ? old('end_date') : $model->formatEndDate()}}" readonly/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">

        <div class="form-group">
            <label>Nội dung chăm sóc</label>
            <select class="form-control chosen-select" name="status">
                @foreach($status as $key => $val)
                    <option value="{{ $key }}" {{ $key == $model->status || $key == old('status') ? 'selected' : '' }}>{{$val}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Mô tả khách hàng</label>
            <textarea class="form-control" name="customer_note" rows="5">{{$model->customer_note}}</textarea>
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
