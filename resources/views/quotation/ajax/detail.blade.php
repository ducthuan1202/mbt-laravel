@php
/**
* @var $model \App\PriceQuotation
*/
@endphp
<div class="modal-dialog modal-md">
    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">
                Báo giá của <span class="text-danger">{{$model->formatUser()}}</span>
                ngày <span class="text-warning">{{$model->formatQuotationDate()}}</span>
            </h4>
        </div>
        <div class="modal-body" style="padding: 0;">
            <table class="table table-striped" style="margin-bottom: 0">
                <tbody>
                <tr>
                    <td class="text-right">Khách hàng</td>
                    <td>{!! $model->formatCustomer() !!}</td>
                </tr>
                <tr>
                    <td class="text-right">Khu vực</td>
                    <td>{!! $model->formatCustomerCity() !!}</td>
                </tr>
                <tr>
                    <td class="text-right">Địa chỉ lắp đặt</td>
                    <td>{!! $model->setup_at !!}</td>
                </tr>
                <tr>
                    <td class="text-right">Địa chỉ giao hàng</td>
                    <td>{!! $model->delivery_at !!}</td>
                </tr>
                <tr>
                    <td class="text-right">Số lượng</td>
                    <td>{{$model->amount}}</td>
                </tr>
                <tr>
                    <td class="text-right">Giá báo</td>
                    <td>{{$model->formatPrice()}}</td>
                </tr>

                <tr>
                    <td class="text-right">Công suất</td>
                    <td>{{ $model->power }} kvA</td>
                </tr>

                <tr>
                    <td class="text-right">Điện áp vào</td>
                    <td>{{ $model->voltage_input }} kv</td>
                </tr>
                <tr>
                    <td class="text-right">Điện áp ra</td>
                    <td>{{ $model->voltage_output}} kv</td>
                </tr>

                <tr>
                    <td class="text-right">Kiểu máy</td>
                    <td>{!! $model->formatType() !!}</td>
                </tr>

                <tr>
                    <td class="text-right">Ngoại hình máy</td>
                    <td>{!! $model->formatSkin() !!}</td>
                </tr>

                <tr>
                    <td class="text-right">Tiêu chuẩn</td>
                    <td>{!! $model->formatStandard() !!}</td>
                </tr>
                <tr>
                    <td class="text-right">Trạng thái báo giá</td>
                    <td>{!! $model->formatStatus() !!}</td>
                </tr>
                <tr>
                    <td class="text-right">Trạng thái khách hàng</td>
                    <td>{!! $model->formatOrderStatus() !!}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
