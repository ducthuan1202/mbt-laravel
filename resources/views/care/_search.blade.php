
<div class="well" style="overflow: auto">
    <form class="form" action="{{route('cares.index')}}" method="GET">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Tìm theo nội dung</label>
                    <input type="text" class="form-control" name="keyword" value="{{$searchParams['keyword'] ? $searchParams['keyword'] : ''}}"/>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Tìm theo nhân viên</label>
                    <select class="form-control" name="city">
                        @foreach($users as $user)
                            <option value="{{ $user['id'] }}" {{ $user['id'] == $searchParams['user'] ? 'selected' : '' }}>{{$user['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Tìm theo khách hàng</label>
                    <select class="form-control" name="customer">
                        @foreach($customers as $customer)
                            <option value="{{ $customer['id'] }}" {{ $customer['id'] == $searchParams['customer'] ? 'selected' : '' }}>{{$customer['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Tìm theo tình trạng</label>

                    <select class="form-control" name="status">
                        @foreach($status as $key => $val)
                            <option value="{{ $key }}" {{ $key == $searchParams['status'] ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                    <a href="{{route('cares.index')}}" class="btn btn-default">Bỏ Lọc</a>
                </div>
            </div>
        </div>
    </form>
</div>
