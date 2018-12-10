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
    <select class="form-control" name="product_skin_id">
        @foreach($skins as $skin)
            <option value="{{ $skin['id'] }}" {{ $skin['id'] == $model->product_skin_id ? 'selected' : '' }}>{{$skin['name']}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Điện Áp</label>
    <input type="text" class="form-control" name="capacity" value="{{old('capacity') ? old('capacity') : $model->capacity}}"/>
</div>

<div class="form-group">
    <label>Điện Áp Đầu Vào</label>
    <input type="text" class="form-control" name="voltage_input" value="{{old('voltage_input') ? old('voltage_input') : $model->voltage_input}}"/>
</div>

<div class="form-group">
    <label>Điện Áp Đầu Ra</label>
    <input type="text" class="form-control" name="voltage_output" value="{{old('voltage_output') ? old('voltage_output') : $model->voltage_output}}"/>
</div>

<div class="form-group">
    <label>Giá Bán</label>
    <input type="text" class="form-control" name="price" value="{{old('price') ? old('price') : $model->price}}"/>
</div>

<div class="form-group">
    <label>Tên</label>
    <input type="text" class="form-control" name="name" value="{{old('name') ? old('name') : $model->name}}"/>
</div>

<div class="form-group">
    <label>Tiêu Chuẩn</label>
    <input type="text" class="form-control" name="standard" value="{{old('standard') ? old('standard') : $model->standard}}"/>
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
