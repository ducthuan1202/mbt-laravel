@php
    /**
     * @var $model \App\PriceQuotation
     */
@endphp

<div class="well" style="overflow: auto">
    <form class="form" action="{{route('quotations.index')}}" method="GET">

        <input type="hidden" name="status" value="{{$searchParams['status']}}"/>

        <div class="row">

            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
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
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <div class="form-group">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" name="city" id="city_id" onchange="getCustomerByCityAndUser()">
                        <option value="{{$searchParams['city']}}">đang tải dữ liệu</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <div class="form-group">
                    <label>Khách hàng</label>
                    <select class="form-control chosen-select" name="customer" id="customer_id">
                        <option value="{{$searchParams['customer']}}">đang tải dữ liệu</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <div class="form-group">
                    <label>Công suất</label>
                    <input type="text" name="power" class="form-control" value="{{$searchParams['power']}}"/>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <div class="form-group">
                    <label>Nơi lắp</label>
                    <input type="text" name="setup_at" class="form-control" value="{{$searchParams['setup_at']}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
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
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                </div>
            </div>
        </div>
    </form>
</div>
