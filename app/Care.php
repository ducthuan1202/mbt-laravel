<?php

namespace App;

use App\Helpers\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Care
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property string user_id
 * @property string customer_id
 * @property string start_date
 * @property string end_date
 * @property string customer_note
 * @property string status
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property User user
 * @property Customer customer
 *
 */
class Care extends Model
{
    const LIMIT = 50;

    protected $table = 'cares';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'customer_id', 'start_date', 'end_date', 'customer_note', 'status'
    ];

    public $validateMessage = [
        'user_id.required' => 'Chọn nhân viên chăm sóc.',
        'user_id.min' => 'Chọn nhân viên chăm sóc.',
        'customer_id.required' => 'Chọn khách hàng.',
        'customer_id.min' => 'Chọn khách hàng.',
        'customer_note.required' => 'Mô tả khách hàng không thể bỏ trống.',
        'call_date.required' => 'Chọn ngày chăm sóc.',
        'status.required' => 'Nội dung cuộc chăm sóc không thể bỏ trống.',
    ];

    public $validateRules = [
        'user_id' => 'required|integer|min:1',
        'customer_id' => 'required|integer|min:1',
        'customer_note' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
        'status' => 'required',
    ];

    private function getUserLogin(){
        return Auth::user();
    }

    public function checkBeforeSave()
    {
        if (!empty($this->start_date)) {
            $this->start_date = Common::dmY2Ymd($this->start_date);
        }
        if (!empty($this->end_date)) {
            $this->end_date = Common::dmY2Ymd($this->end_date);
        }
    }

    // TODO:  RELATIONSHIP =====
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    // TODO:  QUERY TO DATABASE =====
    public function search($searchParams = [])
    {

        /**
         * @var $userLogin User
         */
        $model = $this->with(['user', 'customer', 'customer.city']);

        $userLogin = $this->getUserLogin();
        if($userLogin && $userLogin->role !== User::ADMIN_ROLE){
            $model = $model->where('user_id', $userLogin->id);
        }

        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }

        // filter by user
        if (isset($searchParams['user']) && !empty($searchParams['user'])) {
            $model = $model->where('user_id', $searchParams['user']);
        }

        // filter by customer
        if (isset($searchParams['customer']) && !empty($searchParams['customer'])) {
            $model = $model->where('customer_id', $searchParams['customer']);
        }

        // filter by status
        if (isset($searchParams['status']) && !empty($searchParams['status'])) {
            $model = $model->where('status', $searchParams['status']);
        }

        // filter by customer city
        if (isset($searchParams['city']) && !empty($searchParams['city'])) {
            $model = $model->whereHas('customer', function ($query) use ($searchParams) {
                $query->where('city_id', $searchParams['city']);
            });
        }

        // filter by customer buy status
        if (isset($searchParams['buy']) && !empty($searchParams['buy'])) {
            $model = $model->whereHas('customer', function ($query) use ($searchParams) {
                $query->where('status', $searchParams['buy']);
            });
        }

        // filter by quotations_date
        if (isset($searchParams['date']) && !empty($searchParams['date'])) {
            $d = Common::extractDate($searchParams['date']);

            $startDate = Common::dmY2Ymd($d[0]);
            $endDate = Common::dmY2Ymd($d[1]);
            if (preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $startDate) && preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $endDate)) {
                $model = $model->whereBetween('start_date', [$startDate, $endDate]);
            }
        }

        $model = $model->orderBy('id', 'desc');

        return $model->paginate(self::LIMIT);
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

    public function checkCustomerExist($id = 0)
    {
        return $this->where('customer_id', $id)->count();
    }

    public static function countNumber()
    {
        return self::count();
    }

    // TODO:  LIST DATA =====
    public function listStatus($addAll =false)
    {
        $data = [];
        if($addAll){
            $data = [null => 'Tất cả'];
        }
        $data[1] = 'Chăm sóc lại báo giá đã báo';
        $data[2] = 'Xin việc';
        $data[3] = 'Để báo giá';
        $data[4] = 'Tư vấn về sản phẩm';
        $data[5] = 'Chốt đơn hàng';
        $data[6] = 'Làm hợp đồng';
        $data[7] = 'Giục lấy hàng';
        $data[8] = 'Giục tạm ứng';
        $data[9] = 'Đòi nợ';
        $data[10] = 'Đòi hợp đồng';
        $data[11] = 'Xin đối chiếu công nợ';
        $data[12] = 'Giới thiệu sản phẩm (khách mới)';
        $data[13] = 'Chúc mừng sinh nhật khách hàng';
        return $data;
    }

    // TODO:  FORMAT =====
    public function formatStatus()
    {
        $listStatus = $this->listStatus();
        if (isset($listStatus[$this->status])) {
            return $listStatus[$this->status];
        }
        return Common::UNKNOWN_TEXT;
    }

    public function formatUser()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return Common::UNKNOWN_TEXT;
    }

    public function formatCustomer($separator = '-')
    {
        if ($this->customer) {
            return sprintf('%s %s <small>%s</small>', $this->customer->name,$separator, $this->customer->mobile);
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

    public function formatEndDate()
    {
        return Common::formatDate($this->end_date);
    }

    public function formatStartDate()
    {
        return Common::formatDate($this->start_date);
    }

}
