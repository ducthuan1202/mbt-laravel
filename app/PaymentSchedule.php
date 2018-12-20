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
    protected $fillable = ['order_id', 'money', 'payment_date', 'status',];

    public $validateMessage = [
        'order_id.required' => 'Chọn đơn hàng cần lên lịch thanh toán.',
        'money.required' => 'Số tiền thanh toán không thể bỏ trống.',
        'payment_date.required' => 'Chọn ngày thanh toán.',
        'status.required' => 'Chọn trạng thái thanh toán.',
    ];

    public $validateRules = [
        'order_id' => 'required',
        'money' => 'required',
        'payment_date' => 'required',
        'status' => 'required',
    ];

    // TODO:  RELATIONSHIP =====
    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'orderid');
    }

    // TODO:  QUERY TO DATABASE =====
    public static function countNumber()
    {
        return self::count();
    }

    public function search($searchParams = [])
    {
        $model = $this->with(['order']);

        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }

        // filter by status
        if (isset($searchParams['status']) && !empty($searchParams['status'])) {
            $model = $model->where('status', $searchParams['status']);
        }

        // order by id desc
        $model = $model->orderBy('id', 'desc');

        return $model->paginate(self::LIMIT);
    }

    // TODO:  LIST DATA =====

    public function listStatus()
    {
        return [
            null => 'Tất cả',
            self::PAID_STATUS => 'Đã thanh toán',
            self::PENDING_STATUS => 'Hẹn thanh toán',
            self::DELAY_STATUS => 'Chậm thanh toán',
        ];
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


    private function formatMoney()
    {
        return Common::formatMoney($this->money);
    }

}
