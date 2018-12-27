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


    protected $table = 'payment_schedules';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id', 'money', 'payment_date', 'status', 'note'];

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
        return $this->where('order_id', $this->order_id)
            ->orderBy('payment_date', 'asc')->get();
    }

    public function getPayment($date)
    {
        $date = Common::extractDate($date);
        $startDate = Common::dmY2Ymd($date[0]);
        $endDate = Common::dmY2Ymd($date[1]);

        return self::with(['order'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', PaymentSchedule::PENDING_STATUS)
            ->get();
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
