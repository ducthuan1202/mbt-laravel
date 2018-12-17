@php
/**
* @var $model \App\Order
*/
@endphp
<div class="modal-dialog modal-md">
    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">
                Đơn hàng <span class="text-danger">{{$model->code}}</span>
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
                    <td class="text-right">Ngày vào sản xuất</td>
                    <td>{!! $model->formatStartDate() !!}</td>
                </tr>
                <tr>
                    <td class="text-right">Ngày giao hàng dự tính</td>
                    <td>{!! $model->formatShippedDate() !!}</td>
                </tr>
                <tr>
                    <td class="text-right">Ngày giao hàng thực tế</td>
                    <td>{!! $model->formatShippedDateReal() !!}</td>
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
                    <td class="text-right">Số lượng x đơn giá = thành tiền </td>
                    <td>{!!  sprintf('%s x %s = <code>%s</code>', $model->amount, $model->formatPrice(), $model->formatTotalMoney()) !!}</td>
                </tr>
                <tr>
                    <td class="text-right">Giá báo</td>
                    <td>{{ $model->formatPrice() }}</td>
                </tr>
                <tr>
                    <td class="text-right">Thành tiền</td>
                    <td>
                        {!!  sprintf('%s x %s = <code>%s</code>', $model->amount, $model->formatPrice(), $model->formatTotalMoney()) !!}
                    </td>
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
                    <td class="text-right">Bảo hành</td>
                    <td>{{$model->guarantee}} tháng</td>
                </tr>
                <tr>
                    <td class="text-right">Tiêu chuẩn</td>
                    <td>{!! $model->formatStandard() !!}</td>
                </tr>
                <tr>
                    <td class="text-right">Trạng thái đơn hàng</td>
                    <td>{!! $model->formatStatus() !!}</td>
                </tr>
                <tr>
                    <td class="text-right">Ghi chú đơn hàng</td>
                    <td>{!! $model->note !!}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
