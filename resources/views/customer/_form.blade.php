@php
    /**
     * @var $model \App\Customer
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
    <div class="col-xs-12 col-sm-6 col-md-6">

        <div class="form-group">
            <label>Họ tên</label>
            <input type="text" class="form-control" name="name" value="{{old('name') ? old('name') : $model->name}}" required/>
        </div>

        <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" class="form-control" name="mobile" value="{{old('mobile') ? old('mobile') : $model->mobile}}" required/>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Chức vụ</label>
                    <input type="text" class="form-control" name="position" value="{{old('position') ? old('position') : $model->position}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Công ty KH</label>
                    <input type="text" class="form-control" name="company" value="{{old('company') ? old('company') : $model->company}}"/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6">

        <div class="form-group">
            <label>NVKD chăm sóc</label>
            <select class="form-control chosen-select" name="user_id">
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ $user['id'] == $model->user_id ? 'selected' : '' }}>{{$user['name']}}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text" class="form-control" name="address" value="{{old('address') ? old('address') : $model->address}}"/>
                </div>
                <div class="form-group">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" name="city_id">
                        @foreach($cities as $city)
                            <option value="{{ $city['id'] }}" {{ $city['id'] == $model->city_id ? 'selected' : '' }}>{{$city['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>Doanh số trung bình</label>
                    <input type="text" class="form-control" name="average_sale"
                           value="{{old('average_sale') ? old('average_sale') : $model->average_sale}}"/>
                </div>

                <div class="form-group">
                    <label>Trạng thái</label>
                    <select class="form-control chosen-select" name="status" onchange="MBT_Customer.switchBuyStatus()">
                        @foreach($status as $key => $val)
                            <option value="{{ $key }}" {{ $key == $model->status ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
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
    <a href="{{route('customers.index')}}" class="btn btn-default">Trở Về</a>
</div>
