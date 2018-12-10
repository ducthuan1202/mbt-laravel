@php
    /**
     * @var $model \App\City
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

<div class="form-group">
    <label>Tên</label>
    <input type="text" class="form-control" name="name" value="{{old('name') ? old('name') : $model->name}}"/>
</div>

<div class="ln_solid"></div>
<div class="form-group">
    @if($model->exists)
        <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
    @else
        <button type="submit" class="btn btn-success">Tạo Mới</button>
    @endif
    <a href="{{route('cities.index')}}" class="btn btn-info float-right">Trở Về</a>
</div>
