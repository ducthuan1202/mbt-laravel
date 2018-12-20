<?php

namespace App;

use App\Helpers\Common;
use App\Helpers\Messages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Debt
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property string order_id
 * @property string customer_id
 * @property string total_money
 * @property string status
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property Order order
 * @property Customer customer
 *
 */
class Debt extends Model
{
    const LIMIT = 10;

    const OLD_STATUS = 1,
        NEW_STATUS = 2;

    protected $table = 'debts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_id', 'order_id', 'total_money', 'status'];

    public $validateMessage = [
        'total_money.required' => 'Tổng dư nợ không thể bỏ trống.',
        'total_money.numeric' => 'Tổng dư nợ phải là kiểu số.',
        'status.required' => 'Trạng thái không thể bỏ trống.',
    ];

    public $validateRules = [
        'total_money' => 'required|numeric',
        'status' => 'required',
    ];

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function search($searchParams = [])
    {
        $model = $this->with(['order', 'customer', 'order.customer']);
        $model = $model->orderBy('id', 'desc');

        // filter
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }

        if (isset($searchParams['customer']) && !empty($searchParams['customer'])) {
            $model = $model->where('customer_id', $searchParams['customer']);
        }

        if (isset($searchParams['order']) && !empty($searchParams['order'])) {
            $model = $model->where('order_id', $searchParams['order']);
        }

        if (isset($searchParams['status']) && !empty($searchParams['status'])) {
            $model = $model->where('status', $searchParams['status']);
        }

        return $model->paginate(self::LIMIT);
    }

    public function checkCustomerExist($id = 0)
    {
        return $this->where('customer_id', $id)->count();
    }

    public function sumTotalMoney()
    {
        $data = DB::table($this->getTable())
            ->select(DB::raw("SUM(total_money) as count"))
            ->get();

        if ($data && isset($data[0])) {
            return $data[0]->count;
        }
        return 0;
    }

    public function getStatus($addAll = true)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data[self::OLD_STATUS] = 'Nợ cũ';
        $data[self::NEW_STATUS] = 'Nợ mới';

        return $data;
    }

    public function formatStatus()
    {
        $arr = $this->getStatus();
        switch ($this->status) {
            case self::OLD_STATUS:
                $output = $arr[self::OLD_STATUS];
                $cls = 'btn-info';
                break;
            case  self::NEW_STATUS:
                $output = $arr[self::NEW_STATUS];
                $cls = 'btn-default';
                break;
            default:
                $output = 'n/a';
                $cls = 'btn-default';
                break;
        }
        return sprintf('<span class="btn btn-xs btn-round %s" style="width: 80px">%s</span>', $cls, $output);
    }

    public function formatMoney(){
        return Common::formatMoney($this->total_money);
    }

    public function formatOrder()
    {
        if (isset($this->order)) {
            return $this->order->code;
        }
        return '';
    }

    public function formatCustomerUser()
    {
        if (isset($this->customer)) {
            return $this->customer->user->name;
        }
        return '';
    }

    public function formatCustomer()
    {
        if (isset($this->customer)) {
            return $this->customer->name;
        }

        if(isset($this->order)){
            return $this->order->customer->name;
        }

        return '';
    }
}
