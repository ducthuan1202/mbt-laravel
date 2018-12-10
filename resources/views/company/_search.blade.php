
<div class="well" style="overflow: auto">
    <form class="form" action="{{route('companies.index')}}" method="GET">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Tìm theo tên</label>
                    <input type="text" class="form-control" name="keyword" value="{{$searchParams['keyword'] ? $searchParams['keyword'] : ''}}"/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Tìm theo khu vực</label>
                    <select class="form-control" name="city">
                        @foreach($cities as $city)
                            <option value="{{ $city['id'] }}" {{ $city['id'] == $searchParams['city'] ? 'selected' : '' }}>{{$city['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                <a href="{{route('companies.index')}}" class="btn btn-primary">Bỏ Lọc</a>
            </div>
        </div>
    </form>
</div>
