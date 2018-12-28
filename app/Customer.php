<?php

namespace App;

use App\Helpers\Common;
use App\Helpers\Messages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
 * @property string birthday
 * @property string note
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
    const LIMIT = 50;
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
        'name', 'position', 'mobile', 'average_sale', 'status', 'birthday', 'note'
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

    public function checkBeforeSave()
    {
        if (!empty($this->birthday)) {
            $this->birthday = Common::dmY2Ymd($this->birthday);
        }
    }

    private function getUserLogin(){
        return Auth::user();
    }
    // TODO:  RELATIONSHIP =====
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function checkCityExist($id = 0)
    {
        return $this->where('city_id', $id)->count();
    }

    public function countCustomerByStatus()
    {
        /**
         * @var $userLogin User
         */
        $model = DB::table('customers')
            ->select('status AS name', DB::raw("COUNT(status) AS value"))
            ->groupBy('status');

        $userLogin = $this->getUserLogin();
        if($userLogin->role !== User::ADMIN_ROLE){
            $model = $model->where('user_id', $userLogin->id);
        }

        return $model->get();
    }

    // TODO:  QUERY TO DATABASE =====
    public function search($searchParams = [])
    {
        $model = $this->buildQuerySearch($searchParams);
        $model = $model->orderBy('id', 'desc');

        return $model->paginate(self::LIMIT);
    }

    public function countHasBuy($searchParams = [])
    {
        $model = $this->buildQuerySearch($searchParams);
        $model = $model->where('status', self::BUY_STATUS);
        return $model->count();
    }

    private function buildQuerySearch($searchParams = [])
    {

        /**
         * @var $userLogin User
         */
        $model = $this->with(['city', 'user']);

        $userLogin = Auth::user();
        if($userLogin->role !== User::ADMIN_ROLE){
            $model = $model->where('user_id', $userLogin->id);
        }

        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where(function ($query) use ($searchParams) {
                $query->where('name', 'like', "%{$searchParams['keyword']}%")
                    ->orWhere('mobile', 'like', "%{$searchParams['keyword']}%");
            });
        }

        if (isset($searchParams['user']) && !empty($searchParams['user'])) {
            $model = $model->where('user_id', $searchParams['user']);
        }

        if (isset($searchParams['city']) && !empty($searchParams['city'])) {
            $model = $model->where('city_id', $searchParams['city']);
        }

        if (isset($searchParams['status']) && !empty($searchParams['status'])) {
            $model = $model->where('status', $searchParams['status']);
        }

        // filter by quotations_date
        if (isset($searchParams['date']) && !empty($searchParams['date'])) {
            $d = Common::extractDate($searchParams['date']);
            $startDate = Common::dmY2Ymd($d[0]);
            $endDate = Common::dmY2Ymd($d[1]);

            if (preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $startDate) && preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $endDate)) {
                $model = $model->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        return $model;
    }

    public static function countNumber()
    {
        return self::count();
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

    // TODO:  LIST DATA =====
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

    // TODO:  FORMAT =====
    public function formatCity()
    {
        $location = '';
        if (!empty($this->address)) {
//            $location = $this->address;
        }

        if (isset($this->city)) {
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
        if (isset($this->user)) {
            return $this->user->name;
        }
        return Common::UNKNOWN_TEXT;
    }

    public function formatCreatedAt(){
        return Common::formatDate($this->created_at);
    }

    public function formatBirthDay(){
        return Common::formatDate($this->birthday);
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

    public function generateUniqueCode()
    {
        return Common::generateUniqueCode('MBT-KH00000', $this->id);
    }

    public function eChartGenerateData($data)
    {
        $list = $this->getStatus();
        $label = [];
        $value = [];

        foreach ($data as $item):
            if($item->name === self::BUY_STATUS){
                $label[] = $list[self::BUY_STATUS];
                $value[] = [
                    'name'=>$list[self::BUY_STATUS],
                    'value'=>$item->value
                ];
            } elseif($item->name === self::NO_BUY_STATUS){
                $label[] = $list[self::NO_BUY_STATUS];
                $value[] = [
                    'name'=>$list[self::NO_BUY_STATUS],
                    'value'=>$item->value
                ];
            } else {
                $label[] = Messages::UNKNOWN_TEXT;
                $value[] = [
                    'name'=>$list[Messages::UNKNOWN_TEXT],
                    'value'=>$item->value
                ];
            }
        endforeach;


        return \GuzzleHttp\json_encode([
            'label' => $label,
            'data' => $value,
        ]);
    }
}
