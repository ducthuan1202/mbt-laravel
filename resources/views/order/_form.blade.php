@php
    /**
     * @var $model \App\Order
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
        <h3>Thông tin khách hàng</h3>

        <div class="form-group">
            <label>Nhân viên kinh doanh</label>
            <select class="form-control chosen-select" name="user_id" id="user_id" onchange="MBT_Order.getCustomerByCity()">
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ $user['id'] == $model->user_id || $user['id'] == old('user_id') ? 'selected' : '' }}>{{$user['name']}}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" id="city_id" onchange="MBT_Order.getCustomerByCity()">
                        @foreach($cities as $city)
                            <option value="{{ $city['id'] }}"
                                    {{ isset($model->customer) && $city['id'] == $model->customer->city_id ? 'selected' : '' }}>
                                {{$city['name']}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Khách hàng</label>
                    <select class="form-control chosen-select" name="customer_id" id="customer_id">
                        <option value="{{$model->customer_id}}">{{$model->customer->name}}</option>
                    </select>
                </div>
            </div>
        </div>


        <div class="form-group">
            <label>Địa chỉ lắp đặt</label>
            <input type="text" class="form-control" name="setup_at" value="{{old('setup_at') ? old('setup_at') : $model->setup_at}}"/>
        </div>
        <div class="form-group">
            <label>Địa chỉ giao hàng</label>
            <input type="text" class="form-control" name="delivery_at" value="{{old('delivery_at') ? old('delivery_at') : $model->delivery_at}}"/>
        </div>

        <div class="form-group">
            <label>Ghi chú đơn hàng</label>
            <textarea class="form-control" name="note">{{old('note') ? old('note') : $model->note}}</textarea>
        </div>

    </div>

    <div class="col-xs-12 col-sm-6 col-md-6">
        <h3>Thông tin sản phẩm</h3>

        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Số lượng</label>
                    <input type="text" class="form-control" name="amount" onchange="MBT_Order.priceOrAmountOnchange()"
                           value="{{old('amount') ? old('amount') : $model->amount}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Đơn giá</label>
                    <input type="text" class="form-control" name="price" onchange="MBT_Order.priceOrAmountOnchange()"
                           value="{{old('price') ? old('price') : $model->price}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Thành tiền</label>
                    <input type="text" class="form-control" id="total_money" readonly/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Công suất (kvA)</label>
                    <input type="text" class="form-control" name="power" value="{{old('power') ? old('power') : $model->power}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Điện áp vào (kv)</label>
                    <input type="text" class="form-control" name="voltage_input"
                           value="{{old('voltage_input') ? old('voltage_input') : $model->voltage_input}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Điện áp ra (kv)</label>
                    <input type="text" class="form-control" name="voltage_output"
                           value="{{old('voltage_output') ? old('voltage_output') : $model->voltage_output}}"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Số máy</label>
                    <input type="text" class="form-control" name="product_number"
                           value="{{old('product_number') ? old('product_number') : $model->product_number}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Bảo hành (tháng)</label>
                    <input type="text" class="form-control" name="guarantee"
                           value="{{old('guarantee') ? old('guarantee') : $model->guarantee}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Trạng thái đơn hàng</label>
                    <select name="status" class="form-control chosen-select">
                        @foreach($model->listStatus() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->status || $key == old('status')) ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Kiểu máy</label>
                    <select name="product_type" class="form-control chosen-select">
                        @foreach($model->listType() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->product_type || $key == old('product_type')) ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Ngoại hình máy</label>
                    <select name="product_skin" class="form-control chosen-select">
                        @foreach($model->listSkin() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->product_skin || $key == old('product_skin')) ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Tiêu chuẩn máy</label>
                    <select name="standard_output" class="form-control chosen-select">
                        @foreach($model->listStandard() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->standard_output || $key == old('standard_output')) ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
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
                <input type="text" class="form-control drp-single" name="start_date"
                       value="{{old('start_date') ? old('start_date') : $model->formatStartDate()}}" readonly/>
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
                <input type="text" class="form-control drp-single" name="shipped_date"
                       value="{{old('shipped_date') ? old('shipped_date') : $model->formatShippedDate()}}" readonly/>
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
                <input type="text" class="form-control drp-single" name="shipped_date_real"
                       value="{{old('shipped_date_real') ? old('shipped_date_real') : $model->formatShippedDateReal()}}" readonly/>
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
