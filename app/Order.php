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
 * @property string code
 * @property integer amount
 * @property string price
 * @property string total_money
 * @property string power
 * @property string voltage_input
 * @property string voltage_output
 * @property string standard_output
 * @property string standard_real
 * @property string guarantee
 * @property string product_number
 * @property string product_skin
 * @property string product_type
 * @property string setup_at
 * @property integer delivery_at
 * @property string start_date
 * @property string shipped_date
 * @property string shipped_date_real
 * @property string note
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
    const
        SHIPPED_STATUS = 1,
        NOT_SHIPPED_STATUS = 2,
        CANCEL_STATUS = 3;

    const
        MACHINE_SKIN = 1,
        CABIN_SKIN = 2;

    protected $table = 'orders';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'customer_id', 'code', 'amount', 'price', 'total_money', 'power', 'voltage_input', 'voltage_output',
        'standard_output', 'standard_real', 'guarantee', 'product_number', 'product_skin', 'product_type',
        'setup_at', 'delivery_at', 'start_date', 'shipped_date', 'note', 'status'
    ];

    public $validateMessage = [
        'user_id.required' => 'Chọn nhân viên kinh doanh.',
        'customer_id.required' => 'Chọn khách hàng.',
        'amount.required' => 'Số lượng sản phẩm không thể bỏ trống.',
        'price.required' => 'Giá báo không thể bỏ trống.',
        'power.required' => 'Công suất sản phẩm không thể bỏ trống.',
        'voltage_input.required' => 'Điện áp đầu vào không thể bỏ trống.',
        'voltage_output.required' => 'Điện áp đầu ra không thể bỏ trống.',
        'standard_output.required' => 'Tiêu chuẩn máy không thể bỏ trống.',
        'standard_real.required' => 'Tiêu chuẩn xuất thực không thể bỏ trống.',
        'guarantee.required' => 'Thời gian bảo hành không thể bỏ trống.',
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
        'user_id' => 'required',
        'customer_id' => 'required',
        'amount' => 'required',
        'price' => 'required',
        'power' => 'required',
        'voltage_input' => 'required',
        'voltage_output' => 'required',
        'standard_output' => 'required',
        'standard_real' => 'required',
        'guarantee' => 'required',
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
            $this->start_date = $this->dmyToymd($this->start_date);
        }
        if (!empty($this->shipped_date)) {
            $this->shipped_date = $this->dmyToymd($this->shipped_date);
        }

        if (!$this->exists) {
            $this->shipped_date_real = null;
            $this->code = '';
        }

        $this->total_money = (int)$this->price * (int)$this->amount;
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
        $model = $this->with(['user', 'customer', 'customer.city']);

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

        // filter by customer city
        if (isset($searchParams['city']) && !empty($searchParams['city'])) {
            $model = $model->whereHas('customer', function ($query) use ($searchParams) {
                $query->where('city_id', $searchParams['city']);
            });
        }

        // filter by quotations_date
        if (isset($searchParams['date']) && !empty($searchParams['date'])) {
            $d = $this->extractDate($searchParams['date']);

            $startDate = $this->dmyToymd($d[0]);
            $endDate = $this->dmyToymd($d[1]);
            if (preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $startDate) && preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $endDate)) {
                $model = $model->whereBetween('start_date', [$startDate, $endDate]);
            }
        }

        // filter by status
        if (isset($searchParams['status']) && !empty($searchParams['status'])) {
            $model = $model->where('status', $searchParams['status']);
        }

        // order by
        $model = $model->orderBy('id', 'desc');

        return $model->paginate(self::LIMIT);
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
            self::SHIPPED_STATUS => 'Đã giao',
            self::NOT_SHIPPED_STATUS => 'Chưa giao',
            self::CANCEL_STATUS => 'Đã hủy',
        ];
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

    public function formatStandard(){
        $list = $this->listStandard();
        if(isset($list[$this->standard_output])){
            return $list[$this->standard_output];
        }
        return 'kiểu máy khác';
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

    public function formatStartDate()
    {
        return $this->formatDate($this->start_date);
    }

    public function formatShippedDate()
    {
        return $this->formatDate($this->shipped_date);
    }

    public function formatShippedDateReal()
    {
        return $this->formatDate($this->shipped_date_real);
    }

    private function formatDate($date)
    {
        if (empty($date)) {
            return '';
        }
        return date('d/m/Y', strtotime($date));
    }

    public function formatPrice()
    {
        return $this->formatMoney($this->price);
    }

    public function formatTotalMoney()
    {
        return $this->formatMoney($this->total_money);
    }

    private function formatMoney($money)
    {
        return number_format($money) . ',000 đ';
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

    public function generateUniqueCode()
    {
        $number = $this->id;
        if (!is_numeric($number) || $number < 1) return '';
        $len = strlen((string)$number);
        return substr('MBT-DH00000', 0, -$len) . $number;
    }

}
