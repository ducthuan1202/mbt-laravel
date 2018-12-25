@php
    /**
     * @var $model \App\Order
     */
$userId = isset($model->customer) ? $model->customer->user_id : 0;
$cityId = isset($model->customer) ? $model->customer->city_id : 0;
$customerId = old('customer_id') ? old('customer_id') : $model->customer_id;
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

<input type="hidden" class="form-control" name="code" value="{{$model->code}}"/>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <h3>Thông tin khách hàng</h3>
        <div class="ln_solid"></div>
        <div class="form-group">
            <label>Nhân viên kinh doanh</label>
            <select class="form-control chosen-select" name="user_id" id="user_id" onchange="getCitiesAndCustomersByUser()">
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ $user['id'] == $model->user_id || $user['id'] == old('user_id') ? 'selected' : '' }}>{{$user['name']}}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" id="city_id" onchange="getCustomerByCityAndUser()">
                        <option value="{{$cityId}}">{{$cityId}}</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Khách hàng</label>
                    <select class="form-control chosen-select" name="customer_id" id="customer_id">
                        <option value="{{$customerId}}">{{$customerId}}</option>
                    </select>
                </div>
            </div>
        </div>


        <div class="form-group {{$errors->has('setup_at') ? 'has-error' : ''}}">
            <label>Địa chỉ lắp đặt</label>
            <input type="text" class="form-control" name="setup_at" value="{{old('setup_at') ? old('setup_at') : $model->setup_at}}"/>
            @if ($errors->has('setup_at')) <span class="help-block">{{ $errors->first('setup_at') }}</span> @endif
        </div>
        <div class="form-group {{$errors->has('delivery_at') ? 'has-error' : ''}}">
            <label>Địa chỉ giao hàng</label>
            <input type="text" class="form-control" name="delivery_at" value="{{old('delivery_at') ? old('delivery_at') : $model->delivery_at}}"/>
            @if ($errors->has('delivery_at')) <span class="help-block">{{ $errors->first('delivery_at') }}</span> @endif
        </div>

        <div class="form-group {{$errors->has('note') ? 'has-error' : ''}}">
            <label>Ghi chú đơn hàng</label>
            <textarea class="form-control" name="note">{{old('note') ? old('note') : $model->note}}</textarea>
            @if ($errors->has('note')) <span class="help-block">{{ $errors->first('note') }}</span> @endif
        </div>

    </div>

    <div class="col-xs-12 col-sm-6 col-md-6">
        <h3>Thông tin đơn hàng</h3>
        <div class="ln_solid"></div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group {{$errors->has('power') ? 'has-error' : ''}}">
                    <label>Công suất (kvA)</label>
                    <input type="text" class="form-control" name="power" value="{{old('power') ? old('power') : $model->power}}"/>
                    @if ($errors->has('power')) <span class="help-block">{{ $errors->first('power') }}</span> @endif
                </div>
                <div class="form-group {{$errors->has('voltage_input') ? 'has-error' : ''}}">
                    <label>Điện áp vào (kv)</label>
                    <input type="text" class="form-control" name="voltage_input" value="{{old('voltage_input') ? old('voltage_input') : $model->voltage_input}}"/>
                    @if ($errors->has('voltage_input')) <span class="help-block">{{ $errors->first('voltage_input') }}</span> @endif
                </div>

                <div class="form-group {{$errors->has('voltage_output') ? 'has-error' : ''}}">
                    <label>Điện áp ra (kv)</label>
                    <input type="text" class="form-control" name="voltage_output" value="{{old('voltage_output') ? old('voltage_output') : $model->voltage_output}}"/>
                    @if ($errors->has('voltage_output')) <span class="help-block">{{ $errors->first('voltage_output') }}</span> @endif
                </div>

                <div class="form-group {{$errors->has('product_number') ? 'has-error' : ''}}">
                    <label class="control-label">Số máy</label>
                    <input type="text" class="form-control" name="product_number" value="{{old('product_number') ? old('product_number') : $model->product_number}}"/>
                    @if ($errors->has('product_number')) <span class="help-block">{{ $errors->first('product_number') }}</span> @endif
                </div>

                <div class="form-group">
                    <label>VAT (<code>ngàn đồng</code>)</label>
                    <input type="number" class="form-control" name="vat" value="{{old('vat') ? old('vat') : $model->vat}}"/>
                </div>

            </div>

            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Kiểu máy</label>
                    <select name="product_type" class="form-control chosen-select">
                        @foreach($model->listType() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->product_type || $key == old('product_type')) ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Ngoại hình máy</label>
                    <select name="product_skin" class="form-control chosen-select">
                        @foreach($model->listSkin() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->product_skin || $key == old('product_skin')) ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Tiêu chuẩn máy</label>
                    <select name="standard_output" class="form-control chosen-select">
                        @foreach($model->listStandard() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->standard_output || $key == old('standard_output')) ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Tiêu chuẩn xuất thực</label>
                    <select name="standard_real" class="form-control chosen-select">
                        @foreach($model->listStandard() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->standard_real || $key == old('standard_real')) ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group {{$errors->has('guarantee') ? 'has-error' : ''}}">
                    <label>Bảo hành</label>
                    <div class="input-group">
                        <input type="number" min="1" class="form-control" name="guarantee" value="{{old('guarantee') ? old('guarantee') : $model->guarantee}}"/>
                        <code class="input-group-addon">tháng</code>
                    </div>
                    @if ($errors->has('guarantee')) <span class="help-block">{{ $errors->first('guarantee') }}</span> @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group {{$errors->has('amount') ? 'has-error' : ''}}">
                    <label>Số lượng</label>
                    <input type="number" class="form-control" name="amount" onchange="MBT_Order.priceOrAmountOnchange()"
                           value="{{old('amount') ? old('amount') : $model->amount}}"/>
                    @if ($errors->has('amount'))
                        <span class="help-block">{{ $errors->first('amount') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group {{$errors->has('price') ? 'has-error' : ''}}">
                    <label>Đơn giá (<code>ngàn đồng</code>)</label>
                    <input type="number" class="form-control" name="price" onchange="MBT_Order.priceOrAmountOnchange()"
                           value="{{old('price') ? old('price') : $model->price}}"/>
                    @if ($errors->has('price')) <span class="help-block">{{ $errors->first('price') }}</span> @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Tạm ứng (<code>ngàn đồng</code>)</label>
                    <input type="number" class="form-control" name="prepay" value="{{old('prepay') ? old('prepay') : $model->prepay}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Thanh toán</label>
                    <select name="payment_pre_shipped" class="form-control chosen-select">
                        @foreach($model->listPaymentPreShip() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->payment_pre_shipped || $key == old('payment_pre_shipped')) ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group {{$errors->has('product_number') ? 'has-error' : ''}}">
                    <label>Trạng thái đơn hàng</label>
                    <select name="status" class="form-control chosen-select">
                        @foreach($model->listStatus() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->status || $key == old('status')) ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="form-group">
            <label>Ngày vào sản xuất</label>
            <div class="input-group date">
                <span class="input-group-addon">
                   <i class="glyphicon glyphicon-calendar"></i>
                </span>
                <input type="text" class="form-control drp-single" name="start_date" value="{{old('start_date') ? old('start_date') : $model->formatStartDate()}}" readonly/>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="form-group">
            <label>Ngày giao hàng</label>
            <div class="input-group date">
                <span class="input-group-addon">
                   <i class="glyphicon glyphicon-calendar"></i>
                </span>
                <input type="text" class="form-control drp-single" name="shipped_date" value="{{old('shipped_date') ? old('shipped_date') : $model->formatShippedDate()}}" readonly/>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="form-group">
            <label>Ngày giao hàng thực tế</label>
            <div class="input-group date">
                <span class="input-group-addon">
                   <i class="glyphicon glyphicon-calendar"></i>
                </span>
                <input type="text" class="form-control drp-single" name="shipped_date_real" value="{{old('shipped_date_real') ? old('shipped_date_real') : $model->formatShippedDateReal()}}" readonly/>
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
    <a href="{{route('orders.index')}}" class="btn btn-default">Trở Về</a>
</div>
