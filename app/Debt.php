<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Debt
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property string user_id
 * @property string debt_date
 * @property string content
 * @property string amount
 * @property string price
 * @property string vat
 * @property string residual
 * @property string status
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property User user
 * @property Customer customer
 *
 */
class Debt extends Model
{
    const LIMIT = 10;
    const ACTIVATE_STATUS = 'activate',
        DEACTIVATE_STATUS = 'deactivate';

    protected $table = 'debts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','customer_id', 'debt_date', 'content', 'amount','price','vat','residual', 'status'];

    public $validateMessage = [
        'user_id.required' => 'Chọn nhân viên kinh daonh.',
        'customer_id.required' => 'Chọn khách hàng.',
        'debt_date.required' => 'Chọn ngày công nợ.',
        'content.required' => 'Nội dung công nợ không thể bỏ trống.',
        'amount.required' => 'Số lượng không thể bỏ trống.',
        'price.required' => 'Giá không thể bỏ trống.',
        'vat.required' => 'VAT không thể bỏ trống.',
        'residual.required' => 'Số dư không thể bỏ trống.',
        'status.required' => 'Trạng thái không thể bỏ trống.',
    ];

    public $validateRules = [
        'user_id' => 'required',
        'customer_id' => 'required',
        'debt_date' => 'required',
        'content' => 'required',
        'amount' => 'required',
        'price' => 'required',
        'vat' => 'required',
        'residual' => 'required',
        'status' => 'required',
    ];

    public function checkBeforeSave()
    {
        if (!empty($this->debt_date)) {
            $this->debt_date = $this->dmyToymd($this->debt_date);
        }
        if (!$this->exists) {

        }
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function search($searchParams = [])
    {
        $model = $this->with(['user', 'customer']);
        $model = $model->orderBy('id', 'desc');

        // filter
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

    public function formatDebtDate()
    {
        return date('d/m/Y', strtotime($this->debt_date));
    }

    public function dmyToymd($date)
    {
        $date = str_replace('/', '-', $date);
        return date('Y-m-d', strtotime($date));
    }
}
