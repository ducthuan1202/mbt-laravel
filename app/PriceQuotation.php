<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PriceQuotation
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property integer customer_id
 * @property integer product_id
 * @property string quotations_date
 * @property string amount
 * @property string price
 * @property string total_money
 * @property string setup_at
 * @property integer delivery_at
 * @property string customer_status
 * @property string guarantee
 * @property string note
 * @property string status
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property Customer customer
 * @property Product product
 *
 */
class PriceQuotation extends Model
{
    const LIMIT = 10;

    const SIGNED_CUSTOMER_STATUS = 'signed',
        UNSIGNED_CUSTOMER_STATUS = 'unsigned';
    const ACTIVATE_STATUS = 'activate',
        DEACTIVATE_STATUS = 'deactivate';

    protected $table = 'price_quotations';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'product_id', 'quotations_date', 'amount', 'price', 'total_money',
        'setup_at', 'delivery_at', 'customer_status', 'guarantee', 'note', 'status',
    ];

    public $validateMessage = [
        'customer_id.required' => 'Chọn khách hàng.',
        'product_id.required' => 'Chọn sản phẩm.',
        'quotations_date.required' => 'Ngày báo giá không thể bỏ trống.',
        'amount.required' => 'Số lượng SP không thể bỏ trống.',
        'price.required' => 'Giá báo không thể bỏ trống.',
        'setup_at.required' => 'Địa điểm lắp đặt không thể bỏ trống.',
        'delivery_at.required' => 'Nơi giao hàng không thể bỏ trống.',
        'customer_status.required' => 'Trạng thái khách không thể bỏ trống.',
    ];

    public $validateRules = [
        'customer_id' => 'required',
        'product_id' => 'required',
        'quotations_date' => 'required',
        'amount' => 'required|max:6',
        'price' => 'required|max:11',
        'setup_at' => 'required',
        'delivery_at' => 'required',
        'customer_status' => 'required',
    ];

    // relationship with city
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function Product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    // search data
    public function search($searchParams = [])
    {
        $model = $this->with(['city', 'company']);
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
        // filter by product
        if (isset($searchParams['product']) && !empty($searchParams['product'])) {
            $model = $model->where('product_id', $searchParams['product']);
        }
        // filter by status
        if (isset($searchParams['status']) && !empty($searchParams['status'])) {
            $model = $model->where('customer_status', $searchParams['status']);
        }
        // order by id desc
        $model = $model->orderBy('id', 'desc');

        return $model->paginate(self::LIMIT);
    }

    public function getCustomerStatus($addAll = true)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data = array_merge($data, [
            self::SIGNED_CUSTOMER_STATUS => 'Đã Ký HĐ',
            self::UNSIGNED_CUSTOMER_STATUS => 'Chưa Ký HĐ',
        ]);

        return $data;
    }

    public function formatCustomer()
    {
        if ($this->customer) {
            return $this->customer->name;
        }
        return 'không xác định';
    }

    public function formatProduct()
    {
        if ($this->product) {
            return $this->product->name;
        }
        return 'không xác định';
    }

    public function formatCustomerStatus()
    {
        $arr = $this->getCustomerStatus();
        switch ($this->customer_status) {
            case self::SIGNED_CUSTOMER_STATUS:
                $output = $arr[self::SIGNED_CUSTOMER_STATUS];
                $cls = 'btn-info';
                break;
            case  self::UNSIGNED_CUSTOMER_STATUS:
                $output = $arr[self::UNSIGNED_CUSTOMER_STATUS];
                $cls = 'btn-default';
                break;
            default:
                $output = 'n/a';
                $cls = 'btn-default';
                break;
        }
        return sprintf('<span class="btn btn-xs btn-round %s" style="width: 80px">%s</span>', $cls, $output);
    }

    public static function formatMoney($money)
    {
        return number_format($money) . ',000 đ';
    }

    public function dmyToymd($date)
    {
        $date = str_replace('/', '-', $date);
        return date('Y-m-d', strtotime($date));
    }

    public function extractDate($str, $separator = ' - ')
    {
        return explode($separator, $str);
    }
}
