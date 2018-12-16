
<div class="well" style="overflow: auto">
    <form class="form" action="{{route('customers.index')}}" method="GET">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                <div class="form-group">
                    <label>Tên hoặc SĐT</label>
                    <input type="text" class="form-control" name="keyword" value="{{$searchParams['keyword'] ? $searchParams['keyword'] : ''}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-3">
                <div class="form-group">
                    <label class="block">Khu vực</label>
                    <select class="form-control chosen-select" name="city">
                        @foreach($cities as $city)
                            <option value="{{ $city['id'] }}" {{ $city['id'] == $searchParams['city'] ? 'selected' : '' }}>{{$city['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-3">
                <div class="form-group">
                    <label>Tình trạng</label>

                    <select class="form-control chosen-select" name="status">
                        @foreach($status as $key => $val)
                            <option value="{{ $key }}" {{ $key == $searchParams['status'] ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                    <a href="{{route('customers.index')}}" class="btn btn-default">Bỏ Lọc</a>
                </div>
            </div>
        </div>
    </form>
</div>
