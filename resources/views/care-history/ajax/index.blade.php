@php
    /**
    * @var $data \App\CareHistory[]
    */
@endphp
<div class="modal-dialog modal-lg">
    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Lịch sử chăm sóc khách hàng</h4>
        </div>
        <div class="modal-body">

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr class="headings">
                        <th>STT</th>
                        <th>Ngày Gọi</th>
                        <th>Ngày Hẹn Gọi Lại</th>
                        <th>Nội Dung Cuộc Gọi</th>
                        <th>Ghi Chú</th>
                        <th>Trạng Thái</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($data))
                        @foreach($data as $item)
                            <tr>
                                <td style="width: 50px">{{$item->id}}</td>
                                <td>{{$item->formatStartDate()}}</td>
                                <td>{{$item->formatEndDate()}}</td>
                                <td>{!! $item->content !!}</td>
                                <td>{!! $item->customer_note !!}</td>
                                <td>{!! $item->formatStatus() !!}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="100%">Không có dữ liệu.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng </button>
        </div>
    </div>
</div>
<script>initDateRangePickerSingle();</script>