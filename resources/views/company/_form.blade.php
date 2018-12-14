@php
    /**
     * @var $model \App\Company
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
    <label>Khu Vực</label>
    <select class="form-control chosen-select" name="city_id">
        @foreach($cities as $city)
            <option value="{{ $city['id'] }}" {{ $city['id'] == $model->city_id ? 'selected' : '' }}>{{$city['name']}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Tên</label>
    <input type="text" class="form-control" name="name" value="{{old('name') ? old('name') : $model->name}}"/>
</div>

<div class="form-group">
    <label>Mô tả</label>
    <textarea class="form-control" name="desc">{!! old('desc') ? old('desc') : $model->desc !!}</textarea>
</div>

<div class="ln_solid"></div>
<div class="form-group">
    @if($model->exists)
        <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
    @else
        <button type="submit" class="btn btn-success">Tạo Mới</button>
    @endif
    <a href="{{route('companies.index')}}" class="btn btn-default">Trở Về</a>
</div>
