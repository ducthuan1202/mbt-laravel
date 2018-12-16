<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PriceQuotation
 * @package App
 * -------------------------------------
 * @property integer id
 *
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
 *
 */
class PriceQuotation extends Model
{
    const LIMIT = 10;

    const
        SIGNED_ORDER_STATUS = 1,
        UNSIGNED_ORDER_STATUS = 2;
    const
        SUCCESS_STATUS = 1,
        FAIL_STATUS = 2,
        PENDING_STATUS = 3;

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
        'user_id', 'customer_id', 'amount', 'price', 'quotations_date', 'power', 'voltage_input', 'voltage_output','standard_output',
        'guarantee', 'product_skin', 'product_type', 'setup_at', 'delivery_at', 'order_status', 'note', 'reason', 'status',
    ];

    public $validateMessage = [
        'user_id.required' => 'Chọn nhân viên kinh doanh.',
        'customer_id.required' => 'Chọn khách hàng.',
        'amount.required' => 'Số lượng sản phẩm không thể bỏ trống.',
        'price.required' => 'Giá báo không thể bỏ trống.',
        'quotations_date.required' => 'Ngày báo giá không thể bỏ trống.',
        'power.required' => 'Công suất sản phẩm không thể bỏ trống.',
        'voltage_input.required' => 'Điện áp đầu vào không thể bỏ trống.',
        'voltage_output.required' => 'Điện áp đầu ra không thể bỏ trống.',
        'standard_output.required' => 'Tiêu chuẩn không thể bỏ trống.',
        'guarantee.required' => 'thời gian bảo hành không thể bỏ trống.',
        'product_skin.required' => 'Chọn ngoại hình máy.',
        'product_type.required' => 'Chọn kiểu máy.',
        'setup_at.required' => 'Địa chỉ lắp đặt không thể bỏ trống.',
        'delivery_at.required' => 'Địa chỉ giao hàng không thể bỏ trống.',
        'order_status.required' => 'Chọn trạng thái đơn hàng.',
        'status.required' => 'Chọn trạng thái khách hàng.',
    ];

    public $validateRules = [
        'user_id' => 'required',
        'customer_id' => 'required',
        'amount' => 'required',
        'price' => 'required',
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
        'order_status' => 'required',
        'status' => 'required',
    ];

    public function checkBeforeSave()
    {

        if (!empty($this->quotations_date)) {
            $this->quotations_date = $this->dmyToymd($this->quotations_date);
        }

        if(empty($this->reason)){
            $this->reason = '';
        }
        if(empty($this->note)){
            $this->note = '';
        }

        $this->total_money = (int)$this->price * (int)$this->amount;
    }

    // TODO:  RELATIONSHIP =====
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    // TODO:  QUERY TO DATABASE =====
    public static function countNumber()
    {
        return self::count();
    }

    public function search($searchParams = [])
    {
        $model = $this->with(['customer', 'user', 'customer.city']);

        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }

        // filter by customer
        if (isset($searchParams['customer']) && !empty($searchParams['customer'])) {
            $model = $model->where('customer_id', $searchParams['customer']);
        }

        // filter by quotations_date
        if (isset($searchParams['date']) && !empty($searchParams['date'])) {
            $d = $this->extractDate($searchParams['date']);

            $startDate = $this->dmyToymd($d[0]);
            $endDate = $this->dmyToymd($d[1]);
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
            $model = $model->where('customer_status', $searchParams['status']);
        }

        // order by id desc
        $model = $model->orderBy('id', 'desc');

        return $model->paginate(self::LIMIT);
    }

    public function checkCustomerExist($id = 0)
    {
        return $this->where('customer_id', $id)->count();
    }

    // TODO:  LIST DATA =====
    public function listStandard()
    {
        return [
            null => 'Tất cả',
            '1011' => '&#9733; Tiêu chẩn 1011',
            '8525-2010' => '&#9733; Tiêu chẩn 8525-2010',
            '8525-2015' => '&#9733; Tiêu chẩn 8525-2015',
            '3079' => '&#9733; Tiêu chẩn 3079',
            '2608' => '&#9733; Tiêu chẩn 2608',
            'qđ62' => '&#9733; Tiêu chẩn qđ 62',
        ];
    }

    public function listSkin()
    {
        return [
            null => 'Tất cả',
            '1' => '&#9671; Kiểu hở sứ thường',
            '2' => '&#9649; Kiểu hở sứ elbow',
            '3' => '&#9670; Kiểu kín sứ thường',
            '4' => '&#9648; Kiểu kín sứ elbow',
        ];
    }

    public function listType()
    {
        return [
            null => 'Tất cả',
            self::MACHINE_SKIN => '&#9744; Máy',
            self::CABIN_SKIN => '&#9750; Tủ - Trạm',
        ];
    }

    public function listStatus()
    {
        return [
            null => 'Tất cả',
            self::SUCCESS_STATUS => '&#10004; Thành công',
            self::PENDING_STATUS => '&#9990; Đang theo',
            self::FAIL_STATUS => '&#10006; Thất bại',
        ];
    }

    public function listOrderStatus($addAll = true)
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
    public function formatSkin(){
        $list = $this->listSkin();
        if(isset($list[$this->product_skin])){
            return $list[$this->product_skin];
        }
        return 'kiểu máy khác';
    }

    public function formatType(){
        $list = $this->listType();
        if(isset($list[$this->product_type])){
            return $list[$this->product_type];
        }
        return 'kiểu máy khác';
    }

    public function formatCustomer()
    {
        if ($this->customer) {
            return sprintf('%s<br/>%s', $this->customer->name, $this->customer->mobile);
        }
        return 'không xác định';
    }

    public function formatCustomerCity()
    {
        if ($this->customer && isset($this->customer->city)) {
            return $this->customer->city->name;
        }
        return 'không xác định';
    }

    public function formatUser()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return 'không xác định';
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

    public function formatStatus(){
        $list = $this->listStatus();
        if(isset($list[$this->status])){
            return $list[$this->status];
        }
        return 'n/a';
    }

    public function formatPrice(){
        return $this->formatMoney($this->price);
    }

    public function formatTotalMoney(){
        return $this->formatMoney($this->total_money);
    }

    private function formatMoney($money)
    {
        return number_format($money) . ',000 đ';
    }

    public function formatStandard(){
        $list = $this->listStandard();
        if(isset($list[$this->standard_output])){
            return $list[$this->standard_output];
        }
        return 'kiểu máy khác';
    }

    public function formatQuotationDate()
    {
        return date('d/m/Y', strtotime($this->quotations_date));
    }

    public function dmyToymd($date)
    {
        if (preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', $date)) {
            $date = str_replace('/', '-', $date);
            return date('Y-m-d', strtotime($date));
        }
        return $date;
    }

    public function extractDate($str, $separator = ' - ')
    {
        return explode($separator, $str);
    }
}
