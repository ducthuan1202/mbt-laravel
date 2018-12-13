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
        <h3>Thông tin khách hàng</h3>

        <div class="form-group">
            <label>Khách Hàng</label>
            <select class="form-control" name="customer_id">
                @foreach($customers as $customer)
                    <option value="{{ $customer['id'] }}" {{ ($customer['id'] == $model->customer_id || $customer['id'] == old('customer_id')) ? 'selected' : '' }}>{{$customer['name']}}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Địa chỉ lắp đặt</label>
                    <input type="text" class="form-control" name="setup_at" value="{{old('setup_at') ? old('setup_at') : $model->setup_at}}"/>
                </div>
                <div class="form-group">
                    <label>Địa chỉ giao hàng</label>
                    <input type="text" class="form-control" name="delivery_at" value="{{old('delivery_at') ? old('delivery_at') : $model->delivery_at}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Trạng Thái KH</label>
                    <select class="form-control" name="customer_status">
                        @foreach($customerStatus as $key => $val)
                            <option value="{{ $key }}" {{ ($key == $model->customer_status || $key == old('customer_status')) ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Ngày Báo Giá</label>
                    <input type="text" class="form-control drp-single" readonly name="quotations_date"
                           value="{{old('quotations_date') ? old('quotations_date') : $model->formatQuotationDate()}}"/>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Ghi Chú</label>
            <textarea class="form-control" name="note">{!! $model->note !!}</textarea>
        </div>

    </div>

    <div class="col-xs-12 col-sm-6 col-md-6">
        <h3>Thông tin sản phẩm</h3>

        <div class="form-group">
            <label>Sản Phẩm Báo Giá</label>
            <select class="form-control" name="product_id">
                @foreach($products as $product)
                    <option value="{{ $product['id'] }}" {{ ($product['id'] == $model->product_id || $product['id'] == old('product_id')) ? 'selected' : '' }}>{{$product['name']}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Tiêu Chuẩn</label>
            <input type="number" class="form-control" name="amount" value="{{old('amount') ? old('amount') : $model->amount}}"/>
        </div>

        <div class="form-group">
            <label>Giá Bán</label>
            <input type="number" class="form-control" name="price" value="{{old('price') ? old('price') : $model->price}}"/>
        </div>

        <div class="form-group">
            <label>Thời Gian Bảo Hành (tháng)</label>
            <input type="number" class="form-control" name="guarantee" value="{{old('guarantee') ? old('guarantee') : $model->guarantee}}"/>
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
