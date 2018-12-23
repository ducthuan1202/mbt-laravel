
<div class="well" style="overflow: auto">
    <form class="form" action="{{route('debts.index')}}" method="GET">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                <div class="form-group">
                    <label>Nhân viên</label>
                    <select class="form-control chosen-select" name="user" id="sUser" onchange="MBT_Debt.getCustomerByCityIndex()">
                        @foreach($users as $user)
                            <option value="{{ $user['id'] }}" {{ $user['id'] == $searchParams['user'] ? 'selected' : '' }}>{{$user['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                <div class="form-group">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" name="city" id="sCity" onchange="MBT_Debt.getCustomerByCityIndex()">
                        @foreach($cities as $city)
                            <option value="{{ $city['id'] }}" {{ $city['id'] == $searchParams['city'] ? 'selected' : '' }}>{{$city['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                <div class="form-group">
                    <label>Khách hàng</label>
                    <select class="form-control chosen-select" name="customer" id="sCustomer">
                        @foreach($customers as $customer)
                            <option value="{{ $customer['id'] }}" {{ $customer['id'] == $searchParams['customer'] ? 'selected' : '' }}>{{$customer['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                <div class="form-group">
                    <label>Tình trạng</label>
                    <select class="form-control chosen-select" name="type">
                        @foreach($types as $key => $val)
                            <option value="{{ $key }}" {{ $key == $searchParams['type'] ? 'selected' : '' }}>{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-4">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                    <a href="{{route('debts.index')}}" class="btn btn-default">Bỏ Lọc</a>
                </div>
            </div>

        </div>
    </form>
</div>
