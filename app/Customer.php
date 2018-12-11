<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property integer company_id
 * @property integer city_id
 * @property string name
 * @property string position
 * @property string mobile
 * @property string email
 * @property string address
 * @property integer total_sale
 * @property string buy_status
 * @property string status
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property City city
 * @property Company company
 *
 */
class Customer extends Model
{
    const LIMIT = 10;
    const HAS_BUY_STATUS = 'yes',
        NEVER_BUY_STATUS = 'no';
    const ACTIVATE_STATUS = 'activate',
        DEACTIVATE_STATUS = 'deactivate';

    protected $table = 'customers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'city_id', 'name', 'position', 'mobile', 'email',
        'address', 'total_sale', 'buy_status', 'status',
    ];

    public $validateMessage = [
        'company_id.required' => 'Chọn công ty của khách hàng.',
        'city_id.required' => 'Chọn khu vực của khách hàng.',
        'name.required' => 'Tên không thể bỏ trống.',
        'position.required' => 'Chức vụkhông thể bỏ trống.',
        'mobile.required' => 'Số điện thoại không thể bỏ trống.',
        'address.required' => 'Địa chỉ không thể bỏ trống.',
        'buy_status.required' => 'Chọn trạng thái khách.',
    ];

    public $validateRules = [
        'city_id' => 'required',
        'name' => 'required|max:255',
        'position' => 'required|max:255',
        'mobile' => 'required',
        'address' => 'required|max:255',
        'buy_status' => 'required',
    ];

    // relationship with city
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    // search data
    public function search($searchParams = [])
    {
        $model = $this->with(['city', 'company']);
        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }
        // filter by city
        if (isset($searchParams['city']) && !empty($searchParams['city'])) {
            $model = $model->where('city_id', $searchParams['city']);
        }
        // filter by company
        if (isset($searchParams['company']) && !empty($searchParams['company'])) {
            $model = $model->where('company_id', $searchParams['company']);
        }
        // filter by company
        if (isset($searchParams['buy']) && !empty($searchParams['buy'])) {
            $model = $model->where('buy_status', $searchParams['buy']);
        }
        // order by id desc
        $model = $model->orderBy('id', 'desc');

        return $model->paginate(self::LIMIT);
    }

    public function getDropDownList($addAll = true){
        $data =  $this->select('id', 'name')->get()->toArray();

        if ($addAll) {
            $firstItem = ['id' => null, 'name' => 'Tất cả'];
            array_unshift($data, $firstItem);
        }
        return $data;
    }

    public function getBuyStatus($addAll = true)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data = array_merge($data, [
            self::HAS_BUY_STATUS => 'Đã Mua',
            self::NEVER_BUY_STATUS => 'Chưa Mua',
        ]);

        return $data;
    }

    public function formatCity()
    {
        if ($this->city) {
            return $this->city->name;
        }
        return 'không xác định';
    }

    public function formatCompany()
    {
        if ($this->company) {
            return $this->company->name;
        }
        return 'không xác định';
    }

    public function formatBuyStatus()
    {
        $arr = $this->getBuyStatus();
        switch ($this->buy_status){
            case self::HAS_BUY_STATUS:
                $output = $arr[self::HAS_BUY_STATUS];
                $cls = 'btn-info';
                break;
            case  self::NEVER_BUY_STATUS:
                $output = $arr[self::NEVER_BUY_STATUS];
                $cls = 'btn-default';
                break;
            default:
                $output = 'n/a';
                $cls = 'btn-default';
                break;
        }
        return sprintf('<span class="btn btn-xs btn-round %s" style="width: 80px">%s</span>', $cls, $output);
    }
}
