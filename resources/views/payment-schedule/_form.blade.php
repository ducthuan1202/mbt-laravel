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

<input type="hidden" name="order_id" value="{{$order->id}}" />

<div class="row">
    <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="form-group">
            <label>Số tiền</label>
            <input type="text" class="form-control" name="money"
                   value="{{old('money') ? old('money') : $model->money}}" autofocus required/>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="form-group">
            <label>Ngày thanh toán</label>
            <div class="input-group date">
                <span class="input-group-addon">
                   <i class="glyphicon glyphicon-calendar"></i>
                </span>
                <input type="text" class="form-control drp-single" name="payment_date"
                       value="{{old('payment_date') ? old('payment_date') : $model->payment_date}}" readonly/>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="form-group">
            <label>Trạng thái</label>
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
    <button type="button" class="btn btn-success" id="btnSave"
            onclick="MBT_PaymentSchedule.toSave('{{$order->id}}');">Lưu</button>
</div>
