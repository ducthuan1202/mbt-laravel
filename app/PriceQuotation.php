<?php

namespace App;

use App\Helpers\Common;
use App\Helpers\Messages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class PriceQuotation
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property integer code
 * @property integer user_id
 * @property integer customer_id
 * @property integer amount
 * @property string price
 * @property string total_money
 * @property string quotations_date
 * @property string power
 * @property string voltage_input
 * @property string voltage_output
 * @property string standard_output
 * @property string guarantee
 * @property string product_skin
 * @property string product_type
 * @property string setup_at
 * @property integer delivery_at
 * @property string order_status
 * @property string note
 * @property string reason
 * @property string status
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property Customer customer
 * @property User user
 * @property Order order
 *
 */
class PriceQuotation extends Model
{
    const LIMIT = 50;

    const
        SIGNED_ORDER_STATUS = 1,
        UNSIGNED_ORDER_STATUS = 2;
    const
        SUCCESS_STATUS = 1,
        PENDING_STATUS = 2,
        FAIL_STATUS = 3;

    const
        MACHINE_SKIN = 1,
        CABIN_SKIN = 2;

    protected $table = 'price_quotations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'user_id', 'customer_id', 'amount', 'price', 'quotations_date', 'power', 'voltage_input', 'voltage_output', 'standard_output',
        'guarantee', 'product_skin', 'product_type', 'setup_at', 'delivery_at', 'order_status', 'note', 'reason', 'status',
    ];

    public $validateMessage = [
        'user_id.required' => 'Chọn nhân viên kinh doanh.',
        'user_id.min' => 'Chọn nhân viên kinh doanh.',
        'customer_id.required' => 'Chọn khách hàng.',
        'customer_id.min' => 'Chọn khách hàng.',
        'amount.required' => 'Số lượng không thể bỏ trống.',
        'amount.min' => 'Số lượng phải là số và lớn hơn 0.',
        'price.required' => 'Giá báo không thể bỏ trống.',
        'price.min' => 'Giá báo phải là số và lớn hơn 0.',
        'quotations_date.required' => 'Ngày báo giá không thể bỏ trống.',
        'power.required' => 'Công suất không thể bỏ trống.',
        'voltage_input.required' => 'Điện áp đầu vào không thể bỏ trống.',
        'voltage_output.required' => 'Điện áp đầu ra không thể bỏ trống.',
        'standard_output.required' => 'Chọn tiêu chuẩn.',
        'guarantee.required' => 'Thời gian bảo hành không thể bỏ trống.',
        'product_skin.required' => 'Chọn ngoại hình máy.',
        'product_type.required' => 'Chọn kiểu máy.',
        'setup_at.required' => 'Địa chỉ lắp đặt không thể bỏ trống.',
        'delivery_at.required' => 'Địa chỉ giao hàng không thể bỏ trống.',
        'status.required' => 'Chọn trạng thái.',
    ];

    public $validateRules = [
        'user_id' => 'required|integer|min:1',
        'customer_id' => 'required|integer|min:1',
        'amount' => 'required|integer|min:1',
        'price' => 'required|integer|min:1',
        'quotations_date' => 'required',
        'power' => 'required',
        'voltage_input' => 'required',
        'voltage_output' => 'required',
        'standard_output' => 'required',
        'guarantee' => 'required',
        'product_skin' => 'required',
        'product_type' => 'required',
        'setup_at' => 'required',
        'delivery_at' => 'required',
        'status' => 'required',
    ];

    private function getUserLogin(){
        return Auth::user();
    }

    public function checkBeforeSave()
    {

        if (!empty($this->quotations_date)) {
            $this->quotations_date = Common::dmY2Ymd($this->quotations_date);
        }

        if (empty($this->reason)) {
            $this->reason = '';
        }
        if (empty($this->note)) {
            $this->note = '';
        }

        $this->total_money = (int)$this->price * (int)$this->amount;
    }

    // TODO:  RELATIONSHIP =====
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // TODO:  QUERY TO DATABASE =====
    public static function countNumber()
    {
        return self::count();
    }

    public function search($searchParams = [])
    {
        $model = $this->buildQuerySearch($searchParams);

        // order by id desc
        $model = $model->orderBy('id', 'desc');

        return $model->paginate(self::LIMIT);
    }

    public function countByStatus($data = [])
    {
        $count = [
            self::SUCCESS_STATUS => 0,
            self::PENDING_STATUS => 0,
            self::FAIL_STATUS => 0,
        ];

        foreach ($data as $item) {
            switch ($item->status) {
                case self::SUCCESS_STATUS:
                    $count[self::SUCCESS_STATUS]++;
                    break;
                case self::PENDING_STATUS:
                    $count[self::PENDING_STATUS]++;
                    break;
                case self::FAIL_STATUS:
                    $count[self::FAIL_STATUS]++;
                    break;
                default:
                    break;
            }
        }

        return $count;
    }

    private function buildQuerySearch($searchParams = [])
    {
        $model = $this->with(['customer', 'user', 'customer.city', 'order']);

        $userLogin = $this->getUserLogin();
        if($userLogin && $userLogin->role !== User::ADMIN_ROLE){
            $model = $model->where('user_id', $userLogin->id);
        }

        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }

        // filter by customer
        if (isset($searchParams['user']) && !empty($searchParams['user'])) {
            $model = $model->where('user_id', $searchParams['user']);
        }

        // filter by customer
        if (isset($searchParams['customer']) && !empty($searchParams['customer'])) {
            $model = $model->where('customer_id', $searchParams['customer']);
        }

        // filter by quotations_date
        if (isset($searchParams['date']) && !empty($searchParams['date'])) {
            $d = Common::extractDate($searchParams['date']);
            $startDate = Common::dmY2Ymd($d[0]);
            $endDate = Common::dmY2Ymd($d[1]);

            if (preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $startDate) && preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $endDate)) {
                $model = $model->whereBetween('quotations_date', [$startDate, $endDate]);
            }
        }

        // filter by customer city
        if (isset($searchParams['city']) && !empty($searchParams['city'])) {
            $model = $model->whereHas('customer', function ($query) use ($searchParams) {
                $query->where('city_id', $searchParams['city']);
            });
        }

        // filter by status
        if (isset($searchParams['status']) && !empty($searchParams['status'])) {
            $model = $model->where('status', $searchParams['status']);
        }

        return $model;
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

    public function countByDate($date = null){
        if(empty($date)) return 0;

        $date = Common::extractDate($date);
        $startDate = Common::dmY2Ymd($date[0]);
        $endDate = Common::dmY2Ymd($date[1]);

        $data = self::whereBetween('quotations_date', [$startDate, $endDate])->count();
        return $data;
    }

    // TODO:  LIST DATA =====
    public function listStandard($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data['1011'] = 'Tiêu chẩn 1011';
        $data['8525-2010'] = 'Tiêu chẩn 8525-2010';
        $data['8525-2015'] = 'Tiêu chẩn 8525-2015';
        $data['3079'] = 'Tiêu chẩn 3079';
        $data['2608'] = 'Tiêu chẩn 2608';
        $data['qđ62'] = 'Tiêu chẩn qđ 62';
        $data['6306'] = 'Tiêu chẩn 6306';
        $data['po'] = 'Tiêu chẩn Po';
        return $data;
    }

    public function listSkin($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data['1'] = 'Kiểu hở sứ thường';
        $data['2'] = 'Kiểu hở sứ elbow';
        $data['3'] = 'Kiểu kín sứ thường';
        $data['4'] = 'Kiểu kín sứ thường';
        return $data;
    }

    public function listType($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data[self::MACHINE_SKIN] = 'Máy';
        $data[self::CABIN_SKIN] = 'Tủ - Trạm';
        return $data;
    }

    public function listStatus($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data[self::SUCCESS_STATUS] = 'Thành công';
        $data[self::PENDING_STATUS] = 'Đang theo';
        $data[self::FAIL_STATUS] = 'Thất bại';
        return $data;
    }

    public function listOrderStatus($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data[self::SIGNED_ORDER_STATUS] = 'Đã Ký HĐ';
        $data[self::UNSIGNED_ORDER_STATUS] = 'Chưa Ký HĐ';
        return $data;
    }

    // TODO:  FORMAT =====
    public function formatSkin()
    {
        $list = $this->listSkin();
        if (isset($list[$this->product_skin])) {
            return $list[$this->product_skin];
        }
        return Common::UNKNOWN_TEXT;
    }

    public function formatType()
    {
        $list = $this->listType();
        if (isset($list[$this->product_type])) {
            return $list[$this->product_type];
        }
        return Common::UNKNOWN_TEXT;
    }

    public function formatCustomer($separator = '-')
    {
        if ($this->customer) {
            return sprintf('%s %s %s', $this->customer->name, $separator, $this->customer->mobile);
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

    public function formatUser()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return Common::UNKNOWN_TEXT;
    }

    public function formatOrderStatus()
    {
        $arr = $this->listOrderStatus();
        switch ($this->order_status) {
            case self::SIGNED_ORDER_STATUS:
                $output = $arr[self::SIGNED_ORDER_STATUS];
                $cls = 'btn-info';
                break;
            case  self::UNSIGNED_ORDER_STATUS:
                $output = $arr[self::UNSIGNED_ORDER_STATUS];
                $cls = 'btn-default';
                break;
            default:
                $output = 'n/a';
                $cls = 'btn-default';
                break;
        }
        return sprintf('<span class="btn btn-xs btn-round %s" style="width: 80px">%s</span>', $cls, $output);
    }

    public function formatStatus()
    {
        $list = $this->listStatus();
        switch ($this->status) {
            case self::SUCCESS_STATUS:
                $output = $list[self::SUCCESS_STATUS];
                $cls = 'btn-success';
                break;
            case  self::FAIL_STATUS:
                $output = $list[self::FAIL_STATUS];
                $cls = 'btn-danger';
                break;
            case self::PENDING_STATUS:
                $output = $list[self::PENDING_STATUS];
                $cls = 'btn-dark';
                break;
            default:
                $output = 'n/a';
                $cls = 'btn-default';
                break;
        }

        return sprintf('<span class="btn btn-xs btn-round %s" style="width: 80px">%s</span>', $cls, $output);
    }

    public function formatPrice()
    {
        return Common::formatMoney($this->price);
    }

    public function formatTotalMoney()
    {
        return Common::formatMoney($this->total_money);
    }

    public function formatStandard()
    {
        $list = $this->listStandard();
        if (isset($list[$this->standard_output])) {
            return $list[$this->standard_output];
        }
        return Common::UNKNOWN_TEXT;
    }

    public function formatQuotationDate()
    {
        return Common::formatDate($this->quotations_date);
    }

}
