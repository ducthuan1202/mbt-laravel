
<div class="well" style="overflow: auto">
    <form class="form" action="{{route('customers.index')}}" method="GET">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Tên hoặc SĐT</label>
                    <input type="text" class="form-control" name="keyword" value="{{$searchParams['keyword'] ? $searchParams['keyword'] : ''}}"/>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Khu vực</label>
                    <select class="form-control chosen-select" name="city">
                        @foreach($cities as $city)
                            <option value="{{ $city['id'] }}" {{ $city['id'] == $searchParams['city'] ? 'selected' : '' }}>{{$city['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Công ty</label>
                    <select class="form-control chosen-select" name="company">
                        @foreach($companies as $company)
                            <option value="{{ $company['id'] }}" {{ $company['id'] == $searchParams['company'] ? 'selected' : '' }}>{{$company['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Tình trạng mua</label>

                    <select class="form-control" name="buy">
                        @foreach($buyStatus as $key => $val)
                            <option value="{{ $key }}" {{ $key == $searchParams['buy'] ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                    <a href="{{route('customers.index')}}" class="btn btn-default">Bỏ Lọc</a>
                </div>
            </div>
        </div>
    </form>
</div>
