
<div class="well" style="overflow: auto">
    <form class="form" action="{{route('debts.index')}}" method="GET">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Khách Hàng</label>
                    <select class="form-control chosen-select" name="customer">
                        @foreach($customers as $customer)
                            <option value="{{ $customer['id'] }}" {{ $customer['id'] == $searchParams['customer'] ? 'selected' : '' }}>{{$customer['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                    <a href="{{route('debts.index')}}" class="btn btn-default">Bỏ Lọc</a>
                </div>
            </div>
        </div>
    </form>
</div>
