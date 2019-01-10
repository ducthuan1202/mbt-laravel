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

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6">
        <div class="form-group">
            <label>Tên khu vực (tỉnh)</label>
            <input type="text" class="form-control" name="name" value="{{old('name') ? old('name') : $model->name}}" autofocus required/>
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
    <a href="{{route('companies.index')}}" class="btn btn-info float-right">Trở Về</a>
</div>
