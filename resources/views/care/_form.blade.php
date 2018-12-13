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
            <label>Khách Hàng</label>
            <select class="form-control" name="customer_id">
                @foreach($customers as $customer)
                    <option value="{{ $customer['id'] }}" {{ $customer['id'] == $model->customer_id ? 'selected' : '' }}>{{$customer['name']}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Ngày Gọi (click để chọn ngày)</label>
            <div class="input-group date">
                <input type="text" class="form-control drp-single" name="call_date" value="{{old('call_date') ? old('call_date') : $model->formatDate()}}" readonly/>
                <span class="input-group-addon">
                   <i class="glyphicon glyphicon-calendar"></i>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label>Trạng Thái Khách</label>
            <select class="form-control" name="status">
                @foreach($status as $key => $val)
                    <option value="{{ $key }}" {{ $key == $model->status ? 'selected' : '' }}>{{$val}}</option>
                @endforeach
            </select>
        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Nội Dung Cuộc Chăm Sóc</label>
            <textarea class="form-control" name="content" rows="7">{{old('content') ? old('content') : $model->content}}</textarea>
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
