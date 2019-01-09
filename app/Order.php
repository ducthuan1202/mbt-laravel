<?php

namespace App;

use App\Helpers\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class Order
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property integer user_id
 * @property integer customer_id
 * @property string code
 * @property integer amount
 * @property integer price
 * @property integer total_money
 * @property string power
 * @property string voltage_input
 * @property string voltage_output
 * @property string standard_output
 * @property string standard_real
 * @property integer guarantee
 * @property string product_number
 * @property integer product_skin
 * @property string product_type
 * @property string setup_at
 * @property integer delivery_at
 * @property string start_date
 * @property string shipped_date
 * @property string shipped_date_real
 * @property string note
 * @property integer status
 * @property integer vat
 * @property integer prepay_required
 * @property integer date_delay_payment
 * @property integer prepay
 * @property string payment_pre_shipped
 * @property string difference_vat
 * @property string group_work
 * @property string condition_pass
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property User user
 * @property Customer customer
 * @property Debt debt
 * @property PaymentSchedule payments
 *
 */
class Order extends Model
{
    const LIMIT = 3;
    const
        SHIPPED_STATUS = 1,
        NOT_SHIPPED_STATUS = 2,
        CANCEL_STATUS = 3;
    const
        MACHINE_SKIN = 1,
        CABIN_SKIN = 2;
    const
        YES = 1,
        NO = 2;

    protected $table = 'orders';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'customer_id', 'code', 'amount', 'price', 'total_money', 'power', 'voltage_input', 'voltage_output',
        'standard_output', 'standard_real', 'guarantee', 'product_number', 'product_skin', 'product_type',
        'setup_at', 'delivery_at', 'start_date', 'shipped_date', 'shipped_date_real', 'note', 'status', 'vat', 'prepay',
        'payment_pre_shipped', 'prepay_required','date_delay_payment','difference_vat','group_work', 'condition_pass'
    ];

    public $validateMessage = [
        'user_id.required' => 'Chọn nhân viên kinh doanh.',
        'user_id.min' => 'Chọn nhân viên kinh doanh.',
        'customer_id.required' => 'Chọn khách hàng.',
        'customer_id.min' => 'Chọn khách hàng.',
        'amount.required' => 'Số lượng sản phẩm không thể bỏ trống.',
        'amount.integer' => 'Số lượng phải là kiểu số.',
        'price.required' => 'Giá báo không thể bỏ trống.',
        'price.integer' => 'Giá báo phải là kiểu số.',
        'power.integer' => 'Công suất sản phẩm không thể bỏ trống.',
        'voltage_input.required' => 'Điện áp đầu vào không thể bỏ trống.',
        'voltage_output.required' => 'Điện áp đầu ra không thể bỏ trống.',
        'standard_output.required' => 'Tiêu chuẩn máy không thể bỏ trống.',
        'standard_real.required' => 'Tiêu chuẩn xuất thực không thể bỏ trống.',
        'guarantee.required' => 'Thời gian bảo hành không thể bỏ trống.',
        'guarantee.numeric' => 'Thời gian bảo hành phải là kiểu số.',
        'product_number.required' => 'Số máy không thể bỏ trống.',
        'product_skin.required' => 'Chọn ngoại hình máy.',
        'product_type.required' => 'Chọn kiểu máy.',
        'setup_at.required' => 'Địa chỉ lắp đặt không thể bỏ trống.',
        'delivery_at.required' => 'Địa chỉ giao hàng không thể bỏ trống.',
        'start_date.required' => 'Ngày vào sản xuất không thể bỏ trống.',
        'shipped_date.required' => 'Ngày giao hàng không thể bỏ trống.',
        'status.required' => 'Chọn trạng thái khách hàng.',
    ];

    public $validateRules = [
        'user_id' => 'required|integer|min:1',
        'customer_id' => 'required|integer|min:1',
        'amount' => 'required|integer',
        'price' => 'required|integer',
        'power' => 'required',
        'voltage_input' => 'required',
        'voltage_output' => 'required',
        'standard_output' => 'required',
        'standard_real' => 'required',
        'guarantee' => 'required|numeric',
        'product_number' => 'required',
        'product_skin' => 'required',
        'product_type' => 'required',
        'setup_at' => 'required',
        'delivery_at' => 'required',
        'start_date' => 'required',
        'shipped_date' => 'required',
        'status' => 'required',
    ];

    public function checkBeforeSave()
    {
        if (!empty($this->start_date)) {
            $this->start_date = Common::dmY2Ymd($this->start_date);
        }

        if (!empty($this->shipped_date)) {
            $this->shipped_date = Common::dmY2Ymd($this->shipped_date);
        }

        if (!empty($this->shipped_date_real)) {
            $this->shipped_date_real = Common::dmY2Ymd($this->shipped_date_real);
            $this->status = self::SHIPPED_STATUS;
        }

        $this->total_money = (int)$this->price * (int)$this->amount;
    }

    // TODO:  RELATIONSHIP =====
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function priceQuotation()
    {
        return $this->hasOne(User::class, 'code', 'code');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function debt()
    {
        return $this->belongsTo(Debt::class, 'id', 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(PaymentSchedule::class, 'order_id', 'id');
    }

    // TODO:  QUERY TO DATABASE =====
    public function buildQuerySearch($searchParams = [])
    {
        /**
         * @var $userLogin User
         */
        $model = $this->with(['user', 'customer', 'customer.city', 'debt', 'payments' => function ($query) {
            $query->where('status', PaymentSchedule::PAID_STATUS);
        }]);

        $userLogin = Auth::user();
        if ($userLogin->role !== User::ADMIN_ROLE) {
            $model = $model->where('user_id', $userLogin->id);
        }

        // filter by user
        if (isset($searchParams['user']) && !empty($searchParams['user'])) {
            $model = $model->where('user_id', $searchParams['user']);
        }

        // filter by customer
        if (isset($searchParams['customer']) && !empty($searchParams['customer'])) {
            $model = $model->where('customer_id', $searchParams['customer']);
        }

        // filter by customer city
        if (isset($searchParams['city']) && !empty($searchParams['city'])) {
            $model = $model->whereHas('customer', function ($query) use ($searchParams) {
                $query->where('city_id', $searchParams['city']);
            });
        }

        // filter by quotations_date
        if (isset($searchParams['date']) && !empty($searchParams['date'])) {
            $d = Common::extractDate($searchParams['date']);
            $startDate = Common::dmY2Ymd($d[0]);
            $endDate = Common::dmY2Ymd($d[1]);

            if (preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $startDate) && preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $endDate)) {
                $model = $model->whereBetween('shipped_date', [$startDate, $endDate]);
            }
        }

        // filter by status
        if (isset($searchParams['status']) && !empty($searchParams['status'])) {
            $model = $model->where('status', $searchParams['status']);
        }

        return $model;
    }

    public function search($searchParams = [])
    {
        $model = $this->buildQuerySearch($searchParams);

        // order by
        $model = $model->orderBy('id', 'desc');

        return $model->paginate(self::LIMIT);
    }

    public function countByStatus($searchParams = [])
    {
        $city = new City();
        $user = new User();
        $customer = new Customer();

        $orderTbl = $this->getTable();
        $cityTbl = $city->getTable();
        $userTbl = $user->getTable();
        $customerTbl = $customer->getTable();

        $query = DB::table($orderTbl)
            ->join($customerTbl, "$customerTbl.id", "=", "$orderTbl.customer_id")
            ->join($cityTbl, "$customerTbl.city_id", "=", "$cityTbl.id")
            ->join($userTbl, "$userTbl.id", "=", "$orderTbl.user_id")
            ->select(DB::raw("COUNT($orderTbl.id) AS 'value', SUM(total_money) as 'total', $orderTbl.status"));

        if (isset($searchParams['date']) && !empty($searchParams['date'])) {
            $d = Common::extractDate($searchParams['date']);
            $startDate = Common::dmY2Ymd($d[0]);
            $endDate = Common::dmY2Ymd($d[1]);

            if (preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $startDate) && preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $endDate)) {
                $query = $query->whereBetween("$orderTbl.shipped_date", [$startDate, $endDate]);
            }
        }

        if (isset($searchParams['city']) && !empty($searchParams['city'])) {
            $query = $query->where("$cityTbl.id", $searchParams['city']);
        }

        if (isset($searchParams['user']) && !empty($searchParams['user'])) {
            $query = $query->where("$userTbl.id", $searchParams['user']);
        }

        if (isset($searchParams['customer']) && !empty($searchParams['customer'])) {
            $query = $query->where("$orderTbl.customer_id", $searchParams['customer']);
        }

        if (isset($searchParams['status']) && !empty($searchParams['status'])) {
            $query = $query->where("$orderTbl.status", $searchParams['status']);
        }

        $data = $query->groupBy("$orderTbl.status")->get();

        $count = [
            self::SHIPPED_STATUS => [
                'count' => 0,
                'total'=>0
            ],
            self::NOT_SHIPPED_STATUS => [
                'count' => 0,
                'total'=>0
            ],
            self::CANCEL_STATUS => [
                'count' => 0,
                'total'=>0
            ],
        ];

        if (!$data || count($data) < 1) {
        } else {
            foreach ($data as $item) {
                $count[$item->status] = [
                    'count'=>$item->value,
                    'total'=>$item->total,
                ];
            }
        }

        return $count;
    }

    public function listByUser()
    {

        if (empty($this->customer_id)) {
            return [];
        }

        return $this->where('customer_id', $this->customer_id)
            ->orderBy('start_date', 'desc')
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

    public function sumTotalMoney()
    {
        $data = DB::table("orders")
            ->select(DB::raw("SUM(total_money) as count"))
            ->get();

        if ($data && isset($data[0])) {
            return $data[0]->count;
        }
        return 0;
    }

    public function getDropDownList($addAll = false)
    {
        $model = $this->select('id', 'code');

        if (!empty($this->customer_id)) {
            $model = $model->where('customer_id', $this->customer_id);
        }

        $data = $model->get()->toArray();

        if ($addAll) {
            $firstItem = ['id' => null, 'code' => 'Chọn đơn hàng'];
            array_unshift($data, $firstItem);
        }

        return $data;
    }

    public function getPrePay($date)
    {
        $date = Common::extractDate($date);
        $startDate = Common::dmY2Ymd($date[0]);
        $endDate = Common::dmY2Ymd($date[1]);

        return Order::with(['customer'])
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function countByDate($date = null){
        if(empty($date)) return 0;

        $date = Common::extractDate($date);
        $startDate = Common::dmY2Ymd($date[0]);
        $endDate = Common::dmY2Ymd($date[1]);

        $data = self::whereBetween('start_date', [$startDate, $endDate])->count();
        return $data;
    }

    // TODO:  LIST DATA =====
    public function listGroupWork($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data['0'] = 'Chọn tổ đấu';
        }
        $data['1'] = 'Y/Yo-12';
        $data['2'] = 'D/Yo-11';
        $data['3'] = 'Y-D/Yo-12-11';
        $data['4'] = 'D-D/Yo-11';
        return $data;
    }

    public function listConditionPass($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data['0'] = 'Chọn lý do xuất hàng';
        }
        $data['1'] = 'Xuất bán';
        $data['2'] = 'Xuất bảo hành';
        $data['3'] = 'Xuất mượn';
        $data['4'] = 'Xuất đổi';
        return $data;
    }

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
        $data['4'] = 'Kiểu kín sứ elbow';
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

    public function listPaymentPreShip($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data[self::YES] = 'Thanh toán hết trước khi giao';
        $data[self::NO] = 'Thanh toán hết sau khi giao';
        return $data;
    }

    public function listStatus($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data[self::SHIPPED_STATUS] = 'Đã giao';
        $data[self::NOT_SHIPPED_STATUS] = 'Chưa giao';
        $data[self::CANCEL_STATUS] = 'Đã hủy';
        return $data;
    }

    public function listPrePayRequired($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data[self::YES] = 'Có tạm ứng';
        $data[self::NO] = 'Không cần tạm ứng';
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

    public function formatStandard()
    {
        $list = $this->listStandard();
        if (isset($list[$this->standard_output])) {
            return $list[$this->standard_output];
        }
        return Common::UNKNOWN_TEXT;
    }

    public function formatStandardReal()
    {
        $list = $this->listStandard();
        if (isset($list[$this->standard_real])) {
            return $list[$this->standard_real];
        }
        return Common::UNKNOWN_TEXT;
    }

    public function formatGroupWork()
    {
        $list = $this->listGroupWork();
        if (isset($list[$this->group_work])) {
            return $list[$this->group_work];
        }
        return 'n/a';
    }

    public function formatStatus()
    {
        $arr = $this->listStatus();
        switch ($this->status) {
            case self::SHIPPED_STATUS:
                $output = $arr[self::SHIPPED_STATUS];
                $cls = 'btn-info';
                break;
            case  self::NOT_SHIPPED_STATUS:
                $output = $arr[self::NOT_SHIPPED_STATUS];
                $cls = 'btn-default';
                break;
            case  self::CANCEL_STATUS:
                $output = $arr[self::CANCEL_STATUS];
                $cls = 'btn-warning';
                break;
            default:
                $output = 'n/a';
                $cls = 'btn-default';
                break;
        }
        return sprintf('<span class="btn btn-xs btn-round %s" style="width: 80px">%s</span>', $cls, $output);
    }

    public function formatVat()
    {
        return Common::formatMoney($this->vat);
    }

    public function formatDifferenceVat()
    {
        return Common::formatMoney($this->difference_vat);
    }

    public function formatConditionPass(){
        $list = $this->listConditionPass();
        if (isset($list[$this->condition_pass])) {
            return $list[$this->condition_pass];
        }
        return '';
    }

    public function formatProductType(){
        if($this->product_type === self::CABIN_SKIN){
            return 'tủ - trạm';
        }

        if($this->product_type === self::MACHINE_SKIN){
            return 'máy';
        }

        return '';
    }

    public function formatPaymentPreShip()
    {
        $data = $this->listPaymentPreShip();
        if (isset($data[$this->payment_pre_shipped])) {
            return $data[$this->payment_pre_shipped];
        }
        return 'n/a';
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
        if (isset($this->customer)) {
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

    public function getTotalMoneyWithoutPayment()
    {
        return $this->total_money + $this->vat - $this->prepay;
    }

    public function formatDebt()
    {
        if (isset($this->debt)) {
            return Common::formatMoney($this->debt->total_money);
        }
        return Common::formatMoney(0);
    }

    public function formatPayment()
    {
        if (isset($this->payments) && count($this->payments)) {
            $sum = 0;
            foreach ($this->payments as $item) {
                $sum += $item->money;
            }
            return Common::formatMoney($sum);
        }
        return Common::formatMoney(0);
    }

    public function formatStartDate()
    {
        return Common::formatDate($this->start_date);
    }

    public function formatShippedDate()
    {
        return Common::formatDate($this->shipped_date);
    }

    public function formatShippedDateReal()
    {
        return Common::formatDate($this->shipped_date_real);
    }

    public function formatPrice()
    {
        return Common::formatMoney($this->price);
    }

    public function formatTotalMoney()
    {
        return Common::formatMoney($this->total_money);
    }

    public function formatPrePay()
    {
        return Common::formatMoney($this->prepay);
    }

    public function formatPrePayRequired()
    {
        if($this->checkConditionShip()){
            return sprintf('<span class="label label-success"><i class="fa fa-check"></i> Đủ điều kiện</span>');
        }

        return sprintf('<span class="label label-danger"><i class="fa fa-times"></i> Không đủ điều kiện</span>');
    }

    public function generateUniqueCode()
    {
        return Common::generateUniqueCode('MBT-DH00000', $this->id);
    }

    public function getRouteName(){
        switch ($this->status) {
            case Order::SHIPPED_STATUS:
                $routeName = 'orders.shipped';
                break;
            case Order::NOT_SHIPPED_STATUS:
                $routeName = 'orders.no_shipped';
                break;
            case Order::CANCEL_STATUS:
            default:
                $routeName = 'orders.cancel';
                break;
        }
        return $routeName;
    }

    public function checkConditionShip(){
        if($this->prepay_required == self::YES && $this->prepay > 0){
            return true;
        }
        return false;
    }

}
