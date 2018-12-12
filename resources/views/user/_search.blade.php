
<div class="well" style="overflow: auto">
    <form class="form" action="{{route('users.index')}}" method="GET">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Tìm theo Tên hoặc SĐT</label>
                    <input type="text" class="form-control" name="keyword" value="{{$searchParams['keyword'] ? $searchParams['keyword'] : ''}}"/>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Tìm theo nhóm quyền</label>
                    <select class="form-control" name="role">
                        @foreach($roles as $role)
                            <option value="{{ $role['id'] }}" {{ $role['id'] == $searchParams['role'] ? 'selected' : '' }}>{{$role['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                    <a href="{{route('users.index')}}" class="btn btn-default">Bỏ Lọc</a>
                </div>
            </div>
        </div>
    </form>
</div>
