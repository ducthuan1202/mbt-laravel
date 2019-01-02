@php
    /**
     * @var $model \App\PriceQuotation
     */
$userLogin = \Illuminate\Support\Facades\Auth::user();

$userId = isset($model->customer) ? $model->customer->user_id : 0;
$cityId = old('city_id') ? old('city_id') : (isset($model->customer) ? $model->customer->city_id : 0);
$customerId = old('customer_id') ? old('customer_id') : ($model->customer_id ? $model->customer_id : 0);
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
    <div class="col-xs-12 col-sm-5 col-md-5">
        <h3>Thông tin khách hàng</h3>
        <div class="ln_solid"></div>
        <div class="form-group">
            <label>Nhân viên kinh doanh</label>
            @can('admin')
                <select class="form-control chosen-select" name="user_id" id="user_id"
                        onchange="getCitiesAndCustomersByUser()">
                    @foreach($users as $user)
                        <option value="{{ $user['id'] }}" {{ $user['id'] == $model->user_id ? 'selected' : '' }}>{{$user['name']}}</option>
                    @endforeach
                </select>
            @else
                <select class="form-control chosen-select" name="user_id" id="user_id"
                        onchange="getCitiesAndCustomersByUser()">
                    <option value="{{ $userLogin->id}}" selected>{{$userLogin->name}}</option>
                </select>
            @endcan
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" name="city_id" id="city_id"
                            onchange="getCustomerByCityAndUser()">
                        <option value="{{$cityId}}">{{$cityId}}</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group {{$errors->has('customer_id') ? 'has-error' : ''}}">
                    <label>Khách hàng</label>
                    <select class="form-control chosen-select" name="customer_id" id="customer_id">
                        <option value="{{$customerId}}">{{$customerId}}</option>
                    </select>
                    @if ($errors->has('customer_id')) <span
                            class="help-block">{{ $errors->first('customer_id') }}</span> @endif
                </div>
            </div>
        </div>

        <div class="form-group {{$errors->has('setup_at') ? 'has-error' : ''}}">
            <label>Địa chỉ lắp đặt</label>
            <input type="text" class="form-control" name="setup_at"
                   value="{{old('setup_at') ? old('setup_at') : $model->setup_at}}"/>
            @if ($errors->has('setup_at')) <span class="help-block">{{ $errors->first('setup_at') }}</span> @endif
        </div>

        <div class="form-group {{$errors->has('delivery_at') ? 'has-error' : ''}}">
            <label>Địa chỉ giao hàng</label>
            <input type="text" class="form-control" name="delivery_at"
                   value="{{old('delivery_at') ? old('delivery_at') : $model->delivery_at}}"/>
            @if ($errors->has('delivery_at')) <span class="help-block">{{ $errors->first('delivery_at') }}</span> @endif
        </div>

        <div class="form-group {{$errors->has('quotations_date') ? 'has-error' : ''}}">
            <label>Ngày báo giá</label>
            <div class="input-group date">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                <input type="text" class="form-control drp-single" name="quotations_date"
                       value="{{old('quotations_date') ? old('quotations_date') : $model->formatQuotationDate()}}"
                       readonly/>
            </div>
            @if ($errors->has('quotations_date')) <span
                    class="help-block">{{ $errors->first('quotations_date') }}</span> @endif
        </div>

    </div>

    <div class="col-xs-12 col-sm-7 col-md-7">
        <h3>Thông tin sản phẩm</h3>
        <div class="ln_solid"></div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group {{$errors->has('power') ? 'has-error' : ''}}">
                    <label>Công suất (kvA)</label>
                    <input type="text" class="form-control" name="power"
                           value="{{old('power') ? old('power') : $model->power}}"/>
                    @if ($errors->has('power')) <span class="help-block">{{ $errors->first('power') }}</span> @endif
                </div>
                <div class="form-group {{$errors->has('voltage_input') ? 'has-error' : ''}}">
                    <label>Điện áp vào (kv)</label>
                    <input type="text" class="form-control" name="voltage_input"
                           value="{{old('voltage_input') ? old('voltage_input') : $model->voltage_input}}"/>
                    @if ($errors->has('voltage_input')) <span
                            class="help-block">{{ $errors->first('voltage_input') }}</span> @endif
                </div>
                <div class="form-group {{$errors->has('voltage_output') ? 'has-error' : ''}}">
                    <label>Điện áp ra (kv)</label>
                    <input type="text" class="form-control" name="voltage_output"
                           value="{{old('voltage_output') ? old('voltage_output') : $model->voltage_output}}"/>
                    @if ($errors->has('voltage_output')) <span
                            class="help-block">{{ $errors->first('voltage_output') }}</span> @endif
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
                    <label>Tiêu chuẩn</label>
                    <select name="standard_output" class="form-control chosen-select">
                        @foreach($model->listStandard() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->standard_output || $key == old('standard_output')) ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group {{$errors->has('amount') ? 'has-error' : ''}}">
                    <label>Số lượng</label>
                    <input type="number" class="form-control" name="amount"
                           value="{{old('amount') ? old('amount') : $model->amount}}"/>
                    @if ($errors->has('amount')) <span class="help-block">{{ $errors->first('amount') }}</span> @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group {{$errors->has('price') ? 'has-error' : ''}}">
                    <label>Đơn giá (<code>ngàn đồng</code>)</label>
                    <input type="number" class="form-control" name="price"
                           value="{{old('price') ? old('price') : $model->price}}"/>
                    @if ($errors->has('price')) <span class="help-block">{{ $errors->first('price') }}</span> @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group {{$errors->has('guarantee') ? 'has-error' : ''}}">
                    <label>Bảo hành</label>
                    <div class="input-group">
                        <input type="number" min="1" class="form-control" name="guarantee"
                               value="{{old('guarantee') ? old('guarantee') : $model->guarantee}}"/>
                        <code class="input-group-addon">tháng</code>
                    </div>
                    @if ($errors->has('guarantee')) <span
                            class="help-block">{{ $errors->first('guarantee') }}</span> @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group {{$errors->has('status') ? 'has-error' : ''}}">
                    <label>Trạng Thái</label>
                    <select class="form-control chosen-select" name="status" id="status"
                            onchange="MBT_PriceQuotation.statusOnchange()">
                        @foreach($model->listStatus() as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->status || $key == old('status')) ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('status')) <span class="help-block">{{ $errors->first('status') }}</span> @endif
                </div>

            </div>
            <div class="form-group">
                <label id="labelStatus">Lý do</label>
                <textarea class="form-control" name="reason"
                          rows="5">{{old('reason') ? old('reason') : $model->reason}}</textarea>
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
    <a href="{{route('quotations.index')}}" class="btn btn-default">Trở Về</a>
</div>
