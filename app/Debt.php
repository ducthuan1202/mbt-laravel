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
 * @property string type
 * @property string date_create
 * @property string date_pay
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
    const LIMIT = 50;

    const
        OLD_STATUS = 1,
        NEW_STATUS = 2;
    const
        HAS_PAY_TYPE = 1,
        NOT_PAY_TYPE = 2;

    protected $table = 'debts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_id', 'order_id', 'total_money', 'status', 'type', 'date_create', 'date_pay'];

    public $validateMessage = [
        'user_id.required' => 'Chọn nhân viên chăm sóc.',
        'user_id.min' => 'Chọn nhân viên chăm sóc.',
        'customer_id.required' => 'Chọn khách hàng.',
        'customer_id.integer' => 'Chọn khách hàng.',
        'customer_id.min' => 'Chọn khách hàng.',
        'total_money.required' => 'Số tiền dư nợ không thể bỏ trống.',
        'total_money.integer' => 'Số tiền dư nợ không thể bỏ trống.',
        'total_money.min' => 'Số tiền dư nợ không thể bỏ trống.',
    ];

    public $validateRules = [
        'user_id' => 'required|integer|min:1',
        'customer_id' => 'required|integer|min:1',
        'total_money' => 'required|integer|min:1',
    ];

    public function checkBeforeSave()
    {
        if(!$this->exists){
            $this->status = self::OLD_STATUS;
            $this->type = self::NOT_PAY_TYPE;
        }
        if (!empty($this->date_create)) {
            $this->date_create = Common::dmY2Ymd($this->date_create);
        }
        if (!empty($this->date_pay)) {
            $this->date_pay = Common::dmY2Ymd($this->date_pay);
        }
    }

    // TODO: RELATIONSHIP =========
    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    // TODO: QUERY DATA =========
    public function search($searchParams = [])
    {
        $model = $this->with(['order', 'customer', 'order.customer']);
        $model = $model->orderBy('id', 'desc');

        if (isset($searchParams['user']) && !empty($searchParams['user'])) {
            $model = $model->whereHas('customer', function ($query) use ($searchParams) {
                $query->where('user_id', $searchParams['user']);
            });
        }

        if (isset($searchParams['city']) && !empty($searchParams['city'])) {
            $model = $model->whereHas('customer', function ($query) use ($searchParams) {
                $query->where('city_id', $searchParams['city']);
            });
        }

        if (isset($searchParams['order']) && !empty($searchParams['order'])) {
            $model = $model->where('order_id', $searchParams['order']);
        }

        if (isset($searchParams['status']) && !empty($searchParams['status'])) {
            $model = $model->where('status', $searchParams['status']);
        }

        if (isset($searchParams['type']) && !empty($searchParams['type'])) {
            $model = $model->where('type', $searchParams['type']);
        }

        return $model->paginate(self::LIMIT);
    }

    public function checkCustomerExist($id = 0)
    {
        return $this->where('customer_id', $id)->count();
    }

    public function listByUser()
    {

        if (empty($this->customer_id)) {
            return [];
        }

        return $this->where('customer_id', $this->customer_id)
            ->orderBy('id', 'desc')
            ->get();
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

    private function getDebt($date){
        $date = Common::extractDate($date);
        $startDate = Common::dmY2Ymd($date[0]);
        $endDate = Common::dmY2Ymd($date[1]);

        return self::with(['customer'])
            ->where('status', Debt::OLD_STATUS)
            ->whereBetween('date_pay', [$startDate, $endDate])
            ->orderBy('date_pay','asc');
    }
    public function getDebtThisTime($date)
    {
        return $this->getDebt($date)
            ->where('type', Debt::HAS_PAY_TYPE)
            ->get();
    }

    public function getDebtNextTime($date)
    {
        return $this->getDebt($date)
            ->where('type', Debt::NOT_PAY_TYPE)
            ->get();
    }

    // TODO: LIST DROPDOWN =========
    public function listStatus($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data[self::OLD_STATUS] = 'Nợ cũ';
        $data[self::NEW_STATUS] = 'Nợ mới';

        return $data;
    }

    public function listType($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data = ['0' => 'Tất cả'];
        }
        $data[self::HAS_PAY_TYPE] = 'Đã thanh toán xong';
        $data[self::NOT_PAY_TYPE] = 'Chưa thanh toán';

        return $data;
    }

    // TODO: FORMAT =========
    public function formatStatus()
    {
        $arr = $this->listStatus();
        switch ($this->status) {
            case self::OLD_STATUS:
                $output = $arr[self::OLD_STATUS];
                $cls = 'btn-dark';
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

    public function formatType()
    {
        $arr = $this->listType();
        switch ($this->type) {
            case self::HAS_PAY_TYPE:
                $output = $arr[self::HAS_PAY_TYPE];
                $cls = 'btn-success';
                break;
            case self::NOT_PAY_TYPE:
                $output = $arr[self::NOT_PAY_TYPE];
                $cls = 'btn-warning';
                break;
            default:
                $output = 'n/a';
                $cls = 'btn-default';
                break;
        }
        return sprintf('<span class="btn btn-xs btn-round %s" style="width: 120px">%s</span>', $cls, $output);
    }

    public function formatMoney(){
        return Common::formatMoney($this->total_money);
    }

    public function formatDateCreate(){
        return Common::formatDate($this->date_create);
    }

    public function formatDatePay(){
        return Common::formatDate($this->date_pay);
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
        if ($this->customer) {
            return sprintf('%s<br/>%s', $this->customer->name, $this->customer->mobile);
        }
        return Common::UNKNOWN_TEXT;
    }

    public function formatCustomerCity()
    {
        if ($this->customer && isset($this->customer->city)) {
            return $this->customer->city->name;
        }
        return Common::UNKNOWN_TEXT;
    }

}
