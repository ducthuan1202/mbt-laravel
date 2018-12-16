@php
    /**
     * @var $model \App\PriceQuotation
     */
@endphp

<div class="well" style="overflow: auto">
    <form class="form" action="{{route('orders.index')}}" method="GET">
        <div class="row">

            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Nhân viên</label>
                    <select class="form-control chosen-select" name="user">
                        @foreach($users as $user)
                            <option value="{{ $user['id'] }}" {{ $user['id'] == $searchParams['user'] ? 'selected' : '' }}>{{$user['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" name="city" id="sCity" onchange="MBT_Order.getCustomerByCityIndex()">
                        @foreach($cities as $city)
                            <option value="{{ $city['id'] }}" {{ $city['id'] == $searchParams['city'] ? 'selected' : '' }}>{{$city['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Khách hàng</label>
                    <select class="form-control chosen-select" name="customer" id="sCustomer">
                        @foreach($customers as $customer)
                            <option value="{{ $customer['id'] }}" {{ $customer['id'] == $searchParams['customer'] ? 'selected' : '' }}>{{$customer['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Trạng thái đơn hàng</label>
                    <select class="form-control chosen-select" name="status">
                        @foreach($model->listStatus() as $key => $val)
                            <option value="{{ $key }}" {{ $key == $searchParams['status'] ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Chọn Ngày</label>
                    <div class="input-group date">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <input type="text" class="form-control drp-multi" name="date"
                               value="{{$searchParams['date'] ? $searchParams['date'] : ''}}" readonly/>
                    </div>

                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                    <a href="{{route('orders.index')}}" class="btn btn-default">Bỏ Lọc</a>
                </div>
            </div>
        </div>
    </form>
</div>
