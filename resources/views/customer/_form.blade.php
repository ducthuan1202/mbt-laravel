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
    <div class="col-md-6">

        <div class="form-group">
            <label>Tên Khách Hàng</label>
            <input type="text" class="form-control" name="name" value="{{old('name') ? old('name') : $model->name}}"/>
        </div>

        <div class="form-group">
            <label>Số Điện Thoại</label>
            <input type="text" class="form-control" name="mobile" value="{{old('mobile') ? old('mobile') : $model->mobile}}"/>

        </div>
        <div class="form-group">
            <label>Địa Chỉ</label>
            <input type="text" class="form-control" name="address" value="{{old('address') ? old('address') : $model->address}}"/>
        </div>
        <div class="form-group">
            <label>Chức Vụ</label>
            <input type="text" class="form-control" name="position" value="{{old('position') ? old('position') : $model->position}}"/>
        </div>

    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>NVKD chăm sóc</label>
            <select class="form-control chosen-select" name="user_id">
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ $user['id'] == $model->user_id ? 'selected' : '' }}>{{$user['name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Khu Vực</label>
            <select class="form-control chosen-select" name="city_id">
                @foreach($cities as $city)
                    <option value="{{ $city['id'] }}" {{ $city['id'] == $model->city_id ? 'selected' : '' }}>{{$city['name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Chọn Công Ty KH</label>
            <select class="form-control chosen-select" name="company_id">
                @foreach($companies as $company)
                    <option value="{{ $company['id'] }}" {{ $company['id'] == $model->company_id ? 'selected' : '' }}>{{$company['name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Trạng Thái Khách</label>
            <select class="form-control" name="buy_status" onchange="MBT_Customer.switchBuyStatus()">
                @foreach($buyStatus as $key => $val)
                    <option value="{{ $key }}" {{ $key == $model->buy_status ? 'selected' : '' }}>{{$val}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Tổng tiền đã giao dịch</label>
            <input type="text" class="form-control" name="total_sale" value="{{old('total_sale') ? old('total_sale') : $model->total_sale}}"/>
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
