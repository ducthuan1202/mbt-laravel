@php
    /**
     * @var $model \App\Debt
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

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-8">
                <div class="form-group">
                    <label>Khách Hàng</label>
                    <select class="form-control chosen-select" name="customer_id">
                        @foreach($customers as $customer)
                            <option value="{{ $customer['id'] }}" {{ ($customer['id'] == $model->customer_id || $customer['id'] == old('customer_id')) ? 'selected' : '' }}>{{$customer['name']}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Thời hạn công nợ</label>
                    <input type="text" class="form-control drp-single" readonly name="debt_date"
                           value="{{old('debt_date') ? old('debt_date') : $model->formatDebtDate()}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-group">
                    <label>NVKD</label>
                    <select class="form-control chosen-select" name="user_id">
                        @foreach($users as $user)
                            <option value="{{ $user['id'] }}" {{ ($user['id'] == $model->user_id || $user['id'] == old('user_id')) ? 'selected' : '' }}>{{$user['name']}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Trạng Thái KH</label>
                    <select class="form-control" name="status">
                        @foreach($status as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->status || $key == old('status')) ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Nội Dung</label>
            <textarea class="form-control" name="content">{{old('content') ? old('content') : $model->content}}</textarea>
        </div>

    </div>

    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            <label>Số Lượng</label>
            <input type="text" class="form-control" name="amount" value="{{old('amount') ? old('amount') : $model->amount}}"/>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-8">
                <div class="form-group">
                    <label>Giá</label>
                    <input type="text" class="form-control" name="price" value="{{old('price') ? old('price') : $model->price}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-group">
                    <label>VAT</label>
                    <input type="text" class="form-control" name="vat" value="{{old('vat') ? old('vat') : $model->vat}}"/>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Số Dư</label>
            <input type="text" class="form-control" name="residual" value="{{old('residual') ? old('residual') : $model->residual}}"/>
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
    <a href="{{route('debts.index')}}" class="btn btn-default">Trở Về</a>
</div>
