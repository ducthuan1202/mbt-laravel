
<div class="well" style="overflow: auto">
    <form class="form" action="{{route('cares.index')}}" method="GET">
        <div class="row">

            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Nhân viên</label>
                    <select class="form-control chosen-select" name="user" id="user_id" onchange="getCitiesAndCustomersByUser()">
                        @foreach($users as $user)
                            <option value="{{ $user['id'] }}" {{ $user['id'] == $searchParams['user'] ? 'selected' : '' }}>{{$user['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" name="city" id="city_id" onchange="getCustomerByCityAndUser()">
                        <option value="{{$searchParams['city']}}">đang tải dữ liệu</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Khách hàng</label>
                    <select class="form-control chosen-select" name="customer" id="customer_id">
                        <option value="{{$searchParams['customer']}}">đang tải dữ liệu</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 hidden">
                <div class="form-group">
                    <label>Trạng thái KH</label>
                    <select class="form-control chosen-select" name="buy">
                        @foreach($buyStatus as $key => $val)
                            <option value="{{ $key }}" {{ $key == $searchParams['buy'] ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Nội dung chăm sóc</label>
                    <select class="form-control chosen-select" name="status">
                        @foreach($status as $key => $val)
                            <option value="{{ $key }}" {{ $key == $searchParams['status'] ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Chọn Ngày</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="text" class="form-control drp-multi" name="date" value="{{$searchParams['date'] ? $searchParams['date'] : ''}}" readonly/>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-2">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                </div>
            </div>
        </div>
    </form>
</div>
