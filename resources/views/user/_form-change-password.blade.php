@php
    /**
     * @var $model \App\User
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
    <div class="col-xs-12 col-sm-8 col-md-6">
        <div class="form-group">
            <label>Mật Khẩu Hiện Tại</label>
            <input type="password" class="form-control" name="old_password" value=""/>
        </div>

        <div class="form-group">
            <label>Mật Khẩu Mới</label>
            <input type="password" class="form-control" name="password" value="{{old('password')}}"/>
        </div>

        <div class="form-group">
            <label>Xác Nhận Mật Khẩu Mới</label>
            <input type="password" class="form-control" name="password_confirmation" value="{{old('password_confirmation')}}"/>
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
    <a href="{{route('users.index')}}" class="btn btn-info float-right">Trở Về</a>
</div>
