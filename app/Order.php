<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Order
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property string user_id
 * @property string customer_id
 * @property string note
 * @property string total_money
 * @property string payment_type
 * @property string start_date
 * @property string shipped_date
 * @property string status
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property User user
 * @property Customer customer
 *
 */
class Order extends Model
{
    const LIMIT = 10;
    const ACTIVATE_STATUS = 'activate',
        DEACTIVATE_STATUS = 'deactivate';

    protected $table = 'orders';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'customer_id', 'note', 'total_money', 'payment_type', 'start_date', 'shipped_date', 'status'];

    public $validateMessage = [
        'user_id.required' => 'Chọn NVKD.',
        'customer_id.required' => 'Chọn khách hàng.',
        'note.required' => 'Ghi chú đơn hàng không thể bỏ trống.',
        'total_money.required' => 'Tổng tiền đơn hàng không thể bỏ trống.',
        'payment_type.required' => 'Chọn hình thức thanh toán.',
        'start_date.required' => 'Chọn ngày đưa vào sản xuất.',
        'shipped_date.required' => 'Chọn ngày giao hàng.',
        'status.required' => 'Trạng thái không thể bỏ trống.',
    ];

    public $validateRules = [
        'user_id' => 'required',
        'customer_id' => 'required',
        'note' => 'required',
        'total_money' => 'required',
        'payment_type' => 'required',
        'start_date' => 'required',
        'shipped_date' => 'required',
        'status' => 'required',
    ];

    public function checkBeforeSave()
    {
        if (!empty($this->start_date)) {
            $this->start_date = $this->dmyToymd($this->start_date);
        }
        if (!empty($this->shipped_date)) {
            $this->shipped_date = $this->dmyToymd($this->shipped_date);
        }
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function search($searchParams = [])
    {
        $model = $this->with(['user', 'customer']);
        $model = $model->orderBy('id', 'desc');

        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }
        if (isset($searchParams['user']) && !empty($searchParams['user'])) {
            $model = $model->where('user_id', $searchParams['user']);
        }
        if (isset($searchParams['customer']) && !empty($searchParams['customer'])) {
            $model = $model->where('customer_id', $searchParams['customer']);
        }
        if (isset($searchParams['status']) && !empty($searchParams['status'])) {
            $model = $model->where('status', $searchParams['status']);
        }
        return $model->paginate(self::LIMIT);
    }

    public function getStatus($addAll = true)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data = array_merge($data, [
            self::ACTIVATE_STATUS => 'Kích Hoạt',
            self::DEACTIVATE_STATUS => 'Tạm Hoãn',
        ]);

        return $data;
    }

    public function formatStatus()
    {
        $arr = $this->getStatus();
        switch ($this->status) {
            case self::ACTIVATE_STATUS:
                $output = $arr[self::ACTIVATE_STATUS];
                $cls = 'btn-info';
                break;
            case  self::DEACTIVATE_STATUS:
                $output = $arr[self::DEACTIVATE_STATUS];
                $cls = 'btn-default';
                break;
            default:
                $output = 'n/a';
                $cls = 'btn-default';
                break;
        }
        return sprintf('<span class="btn btn-xs btn-round %s" style="width: 80px">%s</span>', $cls, $output);
    }

    public function formatUser()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return 'không xác định';
    }

    public function formatCustomer()
    {
        if ($this->customer) {
            return $this->customer->name;
        }
        return 'không xác định';
    }

    public function formatStartDate()
    {
        return date('d/m/Y', strtotime($this->start_date));
    }

    public function formatShippedDate()
    {
        return date('d/m/Y', strtotime($this->shipped_date));
    }

    public function dmyToymd($date)
    {
        $date = str_replace('/', '-', $date);
        return date('Y-m-d', strtotime($date));
    }
}
