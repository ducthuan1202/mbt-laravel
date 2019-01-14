<?php

namespace App;

use App\Helpers\Common;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentSchedule
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property integer order_id
 * @property integer type
 * @property string money
 * @property string payment_date
 * @property string status
 * @property string note
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property Order order
 *
 */
class PaymentSchedule extends Model
{
    const LIMIT = 10;
    const
        PAID_STATUS = 1,
        PENDING_STATUS = 2,
        DELAY_STATUS = 3;
    const
        ORDER_TYPE = 1,
        DEBT_TYPE = 2;

    protected $table = 'payment_schedules';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id', 'type', 'money', 'payment_date', 'status', 'note'];

    public $validateMessage = [
        'order_id.required' => 'Chọn đơn hàng cần lên lịch thanh toán.',
        'money.required' => 'Số tiền thanh toán không thể bỏ trống.',
        'money.numeric' => 'Số tiền thanh toán phải là kiểu số.',
        'payment_date.required' => 'Chọn ngày thanh toán.',
        'status.required' => 'Chọn trạng thái thanh toán.',
    ];

    public $validateRules = [
        'order_id' => 'required',
        'money' => 'required|numeric',
        'payment_date' => 'required',
        'status' => 'required',
    ];

    // TODO:  RELATIONSHIP =====
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    // TODO:  QUERY TO DATABASE =====
    public static function countNumber()
    {
        return self::count();
    }

    public function search()
    {
        $model = $this->where('order_id', $this->order_id)
            ->where('type', $this->type);
        return $model->orderBy('payment_date', 'asc')->get();
    }

    private function getPayment($date)
    {
        $date = Common::extractDate($date);
        $startDate = Common::dmY2Ymd($date[0]);
        $endDate = Common::dmY2Ymd($date[1]);

        return self::with(['order', 'order.customer', 'order.customer.city', 'order.user'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->orderBy('payment_date', 'desc');
    }

    public function getPaymentPaid($date)
    {
        return $this->getPayment($date)
            ->where('status', PaymentSchedule::PAID_STATUS)
            ->get();
    }

    public function getPaymentNextTime($date)
    {
        return $this->getPayment($date)
            ->where('status', PaymentSchedule::PENDING_STATUS)
            ->get();
    }

    public function checkOrderExist($id = 0)
    {
        return $this->where('order_id', $id)->count();
    }

    /**
     * Cron job schedule
     * used: App\Console\Commands\cronPaymentSchedule
     */
    public function updateStatusSchedule(){
        $currentDate = date('Y-m-d');
        $paymentSchedule = self::where('status', self::PENDING_STATUS)
            ->whereDate('payment_date', '<', $currentDate)
            ->get();

        $listId = [];
        if($paymentSchedule){
            foreach ($paymentSchedule as $payment):
                $listId[] =  $payment->id;
                $payment->status = self::DELAY_STATUS;
                $payment->save();
            endforeach;
        }

        // set log file
        $name = sprintf('cronjob/cron_job_%s.txt', date('Y_m_d_H_i_s'));
        $file = fopen(public_path($name), "w") or die("Unable to open file!");
        if(!empty($listId)){
            $content = sprintf('có %s lịch trình đã được sử lý [%s]', join($listId, ','));
        } else {
            $content = 'không có lịch trình nào cần xử lý.';
        }
        fwrite($file, $content);
        fclose($file);

    }

    // TODO:  LIST DATA =====
    public function listStatus($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data[null] = 'Tất cả';
        }
        $data[self::PENDING_STATUS] = 'Hẹn thanh toán';
        $data[self::PAID_STATUS] = 'Đã thanh toán';
        $data[self::DELAY_STATUS] = 'Chậm thanh toán';
        return $data;
    }

    // TODO:  FORMAT =====
    public function formatStatus()
    {
        $list = $this->listStatus();
        if (isset($list[$this->status])) {
            return $list[$this->status];
        }
        return 'n/a';
    }

    public function formatMoney()
    {
        return Common::formatMoney($this->money);
    }

    public function formatDate()
    {
        return Common::formatDate($this->payment_date);
    }
}
