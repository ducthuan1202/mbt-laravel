@php
    /**
     * @var $model \App\PriceQuotation
     */
@endphp

<div class="well" style="overflow: auto">
    <form class="form" action="{{route('quotations.index')}}" method="GET">
        <div class="row">

            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Nhân viên</label>
                    @can('admin')
                        <select class="form-control chosen-select" name="user" id="user_id" onchange="getCitiesAndCustomersByUser()">
                            @foreach($users as $user)
                                <option value="{{ $user['id'] }}" {{ $user['id'] == $searchParams['user'] ? 'selected' : '' }}>{{$user['name']}}</option>
                            @endforeach
                        </select>
                    @elsecan('employee')
                        @php $userLogin = \Illuminate\Support\Facades\Auth::user(); @endphp
                        <select class="form-control chosen-select" name="user" id="user_id">
                            <option value="{{ $userLogin->id}}" selected>{{$userLogin->name}}</option>
                        </select>
                    @endcan

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

            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Trạng thái KH</label>
                    <select class="form-control chosen-select" name="status">
                        @foreach($model->listStatus(true) as $key => $val)
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
                    <a href="{{route('quotations.index')}}" class="btn btn-default">Bỏ Lọc</a>
                </div>
            </div>
        </div>
    </form>
</div>
