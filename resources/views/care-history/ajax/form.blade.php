@php
    /**
    * @var $model \App\CareHistory
    * @var $status array
    */
@endphp
<div class="modal-dialog modal-lg">
    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Thêm mới lịch sử chăm sóc khách hàng</h4>
        </div>
        <div class="modal-body">

            <div id="errors" class="alert alert-danger hidden"></div>

            <form id="care-history-form">
                <input type="hidden" class="form-control" name="care_id" value="{{$model->care_id}}"/>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ngày Gọi Chăm Sóc</label>
                            <input type="text" class="form-control drp-single" name="start_date"
                                   value="{{$model->start_date}}"/>
                        </div>

                        <div class="form-group">
                            <label>Ngày Hẹn Gọi Lại</label>
                            <input type="text" class="form-control drp-single" name="end_date"
                                   value="{{$model->end_date}}"/>
                        </div>

                        <div class="form-group">
                            <label>Trạng Thái Khách</label>
                            <select class="form-control" name="status">
                                @foreach($status as $key => $val)
                                    <option value="{{ $key }}" {{ $key == $model->status ? 'selected' : '' }}>{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Nội Dung</label>
                            <input type="text" class="form-control" name="content" value="{{$model->content}}"/>
                        </div>

                        <div class="form-group">
                            <label>Ghi Chú Khách Hàng</label>
                            <textarea class="form-control" name="customer_note">{{$model->customer_note}}</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="MBT_Care.saveForm()">Lưu</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Hủy và Đóng</button>
        </div>
    </div>
</div>
<script>initDateRangePickerSingle();</script>