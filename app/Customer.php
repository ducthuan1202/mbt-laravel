<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property string code
 * @property integer city_id
 * @property integer user_id
 * @property string company
 * @property string address
 * @property string name
 * @property string position
 * @property string mobile
 * @property integer average_sale
 * @property string status
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property City city
 * @property User user
 *
 */
class Customer extends Model
{
    const LIMIT = 10;
    const
        BUY_STATUS = 1,
        NO_BUY_STATUS = 2;

    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'city_id', 'user_id', 'company', 'address',
        'name', 'position', 'mobile', 'average_sale', 'status'
    ];

    public $validateMessage = [
        'city_id.required' => 'Chọn khu vực của khách hàng.',
        'user_id.required' => 'Chọn NVKD chăm sóc.',
        'name.required' => 'Tên không thể bỏ trống.',
        'mobile.required' => 'Số điện thoại không thể bỏ trống.',
        'status.required' => 'Chọn trạng thái khách.',
    ];

    public $validateRules = [
        'city_id' => 'required',
        'user_id' => 'required',
        'name' => 'required|max:255',
        'mobile' => 'required',
        'status' => 'required',
    ];

    // relationship with city
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function countNumber()
    {
        return self::count();
    }

    public function checkBeforeSave()
    {
        if (!$this->exists) {
            $this->status = self::BUY_STATUS;
        } else {
            $this->code = $this->generateUniqueCode($this->id);
        }
    }

    public function checkCityExist($id = 0)
    {
        return $this->where('city_id', $id)->count();
    }

    // search data
    public function search($searchParams = [])
    {
        $model = $this->with(['city', 'user']);
        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }
        // filter by city
        if (isset($searchParams['city']) && !empty($searchParams['city'])) {
            $model = $model->where('city_id', $searchParams['city']);
        }

        // filter by company
        if (isset($searchParams['status']) && !empty($searchParams['status'])) {
            $model = $model->where('status', $searchParams['status']);
        }

        // order by id desc
        $model = $model->orderBy('id', 'desc');

        return $model->paginate(self::LIMIT);
    }

    public function getDropDownList($addAll = false)
    {
        $model = $this->select('id', 'name', 'mobile');

        if(!empty($this->city_id)){
            $model = $model->where('city_id', $this->city_id);
        }
        if(!empty($this->user_id)){
            $model = $model->where('user_id', $this->user_id);
        }
        $data = $model->get()->toArray();

        if ($addAll) {
            $firstItem = ['id' => null, 'name' => 'Tất cả'];
            array_unshift($data, $firstItem);
        }

        if (!$data) {
            $firstItem = ['id' => null, 'name' => 'Không có dữ liệu'];
            array_unshift($data, $firstItem);
        }

        return $data;
    }

    public function getStatus($addAll = false)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }

        $data[self::BUY_STATUS] = 'Đã Mua';
        $data[self::NO_BUY_STATUS] = 'Chưa Mua';
        return $data;
    }

    public function formatCity()
    {
        $location = '';
        if (!empty($this->address)) {
            $location = $this->address;
        }

        if ($this->city) {
            if (empty($location)) {
                $location = $this->city->name;
            } else {
                $location .= ' - ' . $this->city->name;

            }
        }
        return $location;
    }

    public function formatUser()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return 'không xác định';
    }

    public function formatStatus()
    {
        $arr = $this->getStatus();
        switch ($this->status) {
            case self::BUY_STATUS:
                $output = $arr[self::BUY_STATUS];
                $cls = 'btn-info';
                break;
            case  self::NO_BUY_STATUS:
                $output = $arr[self::NO_BUY_STATUS];
                $cls = 'btn-default';
                break;
            default:
                $output = 'n/a';
                $cls = 'btn-default';
                break;
        }
        return sprintf('<span class="btn btn-xs btn-round %s" style="width: 80px">%s</span>', $cls, $output);
    }

    public function generateUniqueCode($number = 1)
    {
        if (!is_numeric($number) || $number < 1) return '';
        $len = strlen((string)$number);
        return substr('MBT-KH00000', 0, -$len) . $number;
    }
}
