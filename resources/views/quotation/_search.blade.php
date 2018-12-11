
<div class="well" style="overflow: auto">
    <form class="form" action="{{route('quotations.index')}}" method="GET">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Tìm theo tên</label>
                    <input type="text" class="form-control" name="keyword" value="{{$searchParams['keyword'] ? $searchParams['keyword'] : ''}}"/>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Tìm theo ngày</label>
                    <input type="text" class="form-control drp-multi" name="date"
                           value="{{$searchParams['date'] ? $searchParams['date'] : ''}}" readonly/>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Tìm theo SP</label>
                    <select class="form-control" name="product">
                        @foreach($products as $product)
                            <option value="{{ $product['id'] }}" {{ $product['id'] == $searchParams['product'] ? 'selected' : '' }}>{{$product['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Tìm theo trạng thái KH</label>
                    <select class="form-control" name="status">
                        @foreach($customerStatus as $key => $val)
                            <option value="{{ $key }}" {{ $key == $searchParams['status'] ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                    <a href="{{route('quotations.index')}}" class="btn btn-default">Bỏ Lọc</a>
                </div>
            </div>
        </div>
    </form>
</div>
