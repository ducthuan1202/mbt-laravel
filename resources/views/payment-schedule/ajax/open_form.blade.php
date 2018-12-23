@php
    /**
    * @var $model \App\PaymentSchedule
    */
@endphp
<div class="modal-dialog modal-md">
    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Cập nhật lịch thanh toán</h4>
        </div>
        <div class="modal-body">

            <div id="ajaxError" class="alert alert-danger hidden"></div>
            <form id="payment-schedule-form-update">
                <div class="form-group">
                    <label>Số tiền (<code>ngàn đồng</code>)</label>
                    <input type="text" class="form-control" name="money" value="{{$model->money}}" autofocus required/>
                </div>

                <div class="form-group">
                    <label>Ngày thanh toán</label>
                    <div class="input-group date">
                                <span class="input-group-addon">
                                   <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                        <input type="text" class="form-control drp-single" name="payment_date" value="{{$model->formatDate() }}" readonly/>
                    </div>
                </div>

                <div class="form-group">
                    <label>Trạng thái</label>
                    <select class="form-control chosen-select" name="status">
                        @foreach($model->listStatus() as $key => $val)
                            <option value="{{ $key }}" {{ $key == $model->status ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Ghi chú</label>
                    <textarea class="form-control" name="note" rows="3">{{$model->note}}</textarea>
                </div>

            </form>
        </div>

        <div class="modal-footer">
            <button class="btn btn-success" id="button-update-modal" onclick="MBT_PaymentSchedule.saveForm({{$model->id}})">Lưu thông tin</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Hủy bỏ</button>
        </div>

    </div>

</div>
