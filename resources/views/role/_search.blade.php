
<div class="well" style="overflow: auto">
    <form class="form" action="{{route('roles.index')}}" method="GET">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Tìm theo tên</label>
                    <input type="text" class="form-control" name="keyword" value="{{$searchParams['keyword'] ? $searchParams['keyword'] : ''}}"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
            </div>
        </div>
    </form>
</div>
