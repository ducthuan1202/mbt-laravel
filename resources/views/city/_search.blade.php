
<div class="well" style="overflow: auto">
    <form class="form" action="{{route('cities.index')}}" method="GET">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                <div class="form-group">
                    <label>Tìm theo tên</label>
                    <input type="text" class="form-control" name="keyword" value="{{$searchParams['keyword'] ? $searchParams['keyword'] : ''}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                    <a href="{{route('cities.index')}}" class="btn btn-default">Bỏ Lọc</a>
                </div>
            </div>
        </div>
    </form>
</div>
