@php
    /**
     * @var $model \App\User
     */
$user = \Illuminate\Support\Facades\Auth::user();
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
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

        <div class="form-group">
            <label>Số Điện Thoại (dùng để đăng nhập)</label>
            @if (!$model->exists)
                <input type="text" class="form-control" name="mobile" value="{{old('mobile') ? old('mobile') : $model->mobile}}"/>
            @else
                <input type="text" class="form-control" readonly value="{{$model->mobile}}"/>
            @endif
        </div>

        <div class="form-group">
            <label>Tên Hiển Thị</label>
            <input type="text" class="form-control" name="name" value="{{old('name') ? old('name') : $model->name}}"/>
        </div>
        <div class="form-group">
            <label>Email (dùng để đăng nhập)</label>
            <input type="text" class="form-control" name="email" value="{{old('email') ? old('email') : $model->email}}"/>
        </div>

        <div class="row">
            @if (!$model->exists)
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <label>Mật Khẩu</label>
                    <input type="password" class="form-control" name="password" value="{{old('password') ? old('password') : $model->password}}"/>
                </div>
                <div class="form-group">
                    <label>Xác Nhận Mật Khẩu</label>
                    <input type="password" class="form-control" name="password_confirmation" value="{{old('password_confirmation')}}"/>
                </div>
            </div>
            @endif

            <div class="col-xs-12 col-sm-12 {{$model->exists ? 'col-md-12' : 'col-md-6'}}">
                <div class="form-group">
                    <label>Chức Danh</label>
                    <select class="form-control chosen-select" name="role">
                        @foreach($roles as $key => $val)
                            <option value="{{ $key }}" {{ $key == $model->role ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Trạng Thái</label>
                    <select class="form-control chosen-select" name="status">
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
    <a href="{{route('users.index')}}" class="btn btn-info">Trở Về</a>

</div>
