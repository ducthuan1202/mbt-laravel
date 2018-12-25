<div class="well" style="overflow: auto">
    <form class="form" action="{{route('customers.index')}}" method="GET">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Nhân viên</label>
                    <select class="form-control chosen-select" name="user" id="user_id" onchange="getCitiesByUser()">
                        @foreach($users as $user)
                            <option value="{{ $user['id'] }}" {{ $user['id'] == $searchParams['user'] ? 'selected' : '' }}>{{$user['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label class="block">Khu vực</label>
                    <select class="form-control chosen-select" name="city" id="city_id">
                        <option value="{{$searchParams['city']}}">đang tải dữ liệu</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Tình trạng</label>
                    <select class="form-control chosen-select" name="status">
                        @foreach($status as $key => $val)
                            <option value="{{ $key }}" {{ $key == $searchParams['status'] ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Tên hoặc SĐT</label>
                    <input type="text" class="form-control" name="keyword" value="{{$searchParams['keyword'] ? $searchParams['keyword'] : ''}}"/>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Ngày tạo</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="text" class="form-control drp-multi" name="date" value="{{$searchParams['date'] ? $searchParams['date'] : ''}}" readonly/>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                </div>
            </div>
        </div>
    </form>
</div>
