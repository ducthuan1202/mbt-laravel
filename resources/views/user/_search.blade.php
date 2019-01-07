
<div class="well" style="overflow: auto">
    <form class="form" action="{{route('users.index')}}" method="GET">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                <div class="form-group">
                    <label>Tìm theo Tên hoặc SĐT</label>
                    <input type="text" class="form-control" name="keyword" value="{{$searchParams['keyword'] ? $searchParams['keyword'] : ''}}"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="form-group">
                    <label>Chức Danh</label>
                    <select class="form-control chosen-select" name="role">
                        @foreach($roles as $key => $val)
                            <option value="{{ $key }}" {{ $key == $searchParams['role'] ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                </div>
            </div>
        </div>
    </form>
</div>
