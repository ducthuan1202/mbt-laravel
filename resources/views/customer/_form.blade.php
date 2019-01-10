@php
    /**
     * @var $model \App\Customer
     * @var $companies \App\Company[]
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

        <div class="form-group {{$errors->has('name') ? 'has-error' : ''}}">
            <label>Họ tên</label>
            <input type="text" class="form-control" name="name" value="{{old('name') ? old('name') : $model->name}}" required/>
            @if ($errors->has('name')) <span class="help-block">{{ $errors->first('name') }}</span> @endif
        </div>

        <div class="form-group {{$errors->has('mobile') ? 'has-error' : ''}}">
            <label>Số điện thoại</label>
            <input type="text" class="form-control" name="mobile" value="{{old('mobile') ? old('mobile') : $model->mobile}}" required/>
            @if ($errors->has('mobile')) <span class="help-block">{{ $errors->first('mobile') }}</span> @endif
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Chức vụ</label>
                    <input type="text" class="form-control" name="position" value="{{old('position') ? old('position') : $model->position}}"/>
                </div>
                <div class="form-group">
                    <label>Công ty KH</label>
                    <select class="form-control chosen-select" name="company_id">
                        @foreach($companies as $company)
                            <option value="{{ $company['id'] }}" {{ $company['id'] == $model->company_id || $company['id'] == old('company_id') ? 'selected' : '' }}>{{$company['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Ngày sinh</label>
                    <input type="text" class="form-control drp-birthday" name="birthday" value="{{old('birthday') ? old('birthday') : $model->formatBirthDay()}}" readonly/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Đặc điểm</label>
                    <textarea class="form-control" name="note" rows="7">{{old('note') ? old('note') : $model->note}}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6">

        <div class="form-group {{$errors->has('user_id') ? 'has-error' : ''}}">
            <label>NVKD chăm sóc</label>
            <select class="form-control chosen-select" name="user_id">
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ $user['id'] == $model->user_id || $user['id'] == old('user_id') ? 'selected' : '' }}>{{$user['name']}}</option>
                @endforeach
            </select>
            @if ($errors->has('user_id')) <span class="help-block">{{ $errors->first('user_id') }}</span> @endif
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">

                <div class="form-group {{$errors->has('city_id') ? 'has-error' : ''}}">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" name="city_id">
                        @foreach($cities as $city)
                            <option value="{{ $city['id'] }}" {{ $city['id'] == $model->city_id || $city['id'] == old('city_id') ? 'selected' : '' }}>{{$city['name']}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('city_id')) <span class="help-block">{{ $errors->first('city_id') }}</span> @endif
                </div>
                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text" class="form-control" name="address" value="{{old('address') ? old('address') : $model->address}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>Trạng thái</label>
                    <select class="form-control chosen-select" name="status" onchange="MBT_Customer.switchBuyStatus()">
                        @foreach($status as $key => $val)
                            <option value="{{ $key }}" {{ $key == $model->status || $key == old('status') ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Doanh số trung bình</label>
                    <input type="text" class="form-control" name="average_sale"
                           value="{{old('average_sale') ? old('average_sale') : $model->average_sale}}"/>
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
