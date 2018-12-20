@php
    /**
     * @var $model \App\PaymentSchedule
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
            <label>Số tiền thanh toán</label>
            <input type="text" class="form-control" name="money"
                   value="{{old('money') ? old('money') : $model->money}}" autofocus required/>
        </div>
        <div class="form-group">
            <label>Ngày thanh toán</label>
            <input type="text" class="form-control drp-single" name="money"
                   value="{{old('payment_date') ? old('payment_date') : $model->payment_date}}" readonly/>
        </div>
        <div class="form-group">
            <label>Kiểu thanh toán</label>

            <select class="form-control chosen-select" name="status">
                @foreach($model->listStatus() as $key => $val)
                    <option value="{{ $key }}" {{ $key == $model->status ? 'selected' : '' }}>{{$val}}</option>
                @endforeach
            </select>

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
</div>
