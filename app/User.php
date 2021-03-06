<?php

namespace App;

use App\Helpers\Common;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class User
 * @package App
 *
 * @property integer id
 *
 * @property integer role
 * @property string name
 * @property string mobile
 * @property string email
 * @property string password
 * @property string status
 *
 * @property integer created_at
 * @property integer updated_at
 *
 * @property Customer customers
 * @property Care cares
 * @property PriceQuotation quotations
 * @property Order orders
 */
class User extends Authenticatable
{
    use Notifiable;

    const LIMIT = 50;
    const
        EMPLOYEE_ROLE = 2,
        ADMIN_ROLE = 1;
    const
        ACTIVATE_STATUS = 1,
        DEACTIVATE_STATUS = 2;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role', 'name', 'mobile', 'email', 'password', 'status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $validateMessage = [
        'name.required' => 'Tên không thể bỏ trống.',
        'name.max' => 'Tên tối đa 255 ký tự',
        'role.required' => 'Chọn chức danh.',
        'mobile.required' => 'Số điện thoại không thể bỏ trống.',
        'mobile.unique' => 'Số điện thoại đã tồn tại.',
        'password.required' => 'Mật khẩu không thể bỏ trống.',
        'password.min' => 'Mật khẩu tối thiểu phải chứa 6 ký tự.',
        'password.confirmed' => 'Mật khẩu và xác nhận mật khẩu không trùng nhau.',
    ];

    public $validateRules = [
        'name' => 'required|max:255',
        'role' => 'required',
        'mobile' => 'required|unique:users,mobile',
        'password' => ['required', 'string', 'min:6', 'confirmed'],
    ];

    // TODO:  RELATIONSHIP =====
    public function customers()
    {
        return $this->hasMany(Customer::class, 'user_id', 'id');
    }

    public function cares()
    {
        return $this->hasMany(Care::class, 'user_id', 'id');
    }

    public function quotations()
    {
        return $this->hasMany(PriceQuotation::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    // TODO:  QUERY TO DATABASE =====
    public function checkBeforeSave()
    {
        if (!$this->exists) {
            $this->generatePassword();
        }
    }

    public static function countNumber()
    {
        return self::where('role', self::EMPLOYEE_ROLE)->count();
    }

    public function countCustomerByUser()
    {
        // SELECT COUNT(customers.id) AS 'value', users.name FROM users LEFT JOIN customers ON users.id = customers.user_id GROUP BY users.id
        $customerModel = new Customer();
        $customerTbl = $customerModel->getTable();
        $userTbl = $this->getTable();

        return DB::table($userTbl)
            ->select("$userTbl.name AS name", DB::raw("COUNT($customerTbl.id) AS value"))
            ->leftJoin($customerTbl, "$customerTbl.user_id", '=', "$userTbl.id")
            ->where("$userTbl.role", self::EMPLOYEE_ROLE)
            ->groupBy("$userTbl.id")
            ->get();
    }

    public function search($searchParams = [])
    {
        $model = $this->orderBy('id', 'desc');
        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where(function ($query) use ($searchParams) {
                $query->where('name', 'like', "%{$searchParams['keyword']}%")
                    ->orWhere('mobile', 'like', "%{$searchParams['keyword']}%");
            });
        }

        if (isset($searchParams['role']) && !empty($searchParams['role'])) {
            $model = $model->where('role', $searchParams['role']);
        }

        return $model->paginate(self::LIMIT);
    }

    public function getDropDownList($addAll = false)
    {
        $data = $this->select('id', 'name')
            ->where('role', self::EMPLOYEE_ROLE)
            ->get()->toArray();

        if ($addAll) {
            $firstItem = ['id' => '0', 'name' => '--- Chọn NVKD ---'];
            array_unshift($data, $firstItem);
        }
        return $data;
    }

    public function getCustomerByUserCreate($date)
    {
        $date = Common::extractDate($date);
        $startDate = Common::dmY2Ymd($date[0]);
        $endDate = Common::dmY2Ymd($date[1]);

        return User::with(['customer' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }, 'customer.city'])
            ->where('role', User::EMPLOYEE_ROLE)
            ->where('status', User::ACTIVATE_STATUS)
            ->get();
    }

    public function getCustomersCreated($date, $userId = null)
    {

        $d = Common::extractDate($date);
        $startDate = Common::dmY2Ymd($d[0]);
        $endDate = Common::dmY2Ymd($d[1]);

        $query = User::with(['customers' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }, 'customers.city', 'customers.companyName'])
            ->where('role', User::EMPLOYEE_ROLE);

        if (!empty($userId)) {
            $query = $query->where('id', $userId);
            return $query->first();
        } else {
            return $query->get();
        }
    }

    public function getCaresCreated($date, $userId = null)
    {

        $d = Common::extractDate($date);
        $startDate = Common::dmY2Ymd($d[0]);
        $endDate = Common::dmY2Ymd($d[1]);

        $query = User::with(['cares' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate]);
        }, 'cares.customer', 'cares.customer.city', 'cares.customer.companyName'])
            ->where('role', User::EMPLOYEE_ROLE);

        if (!empty($userId)) {
            $query = $query->where('id', $userId);
            return $query->first();
        } else {
            return $query->get();
        }
    }

    public function getQuotationsCreated($date, $userId = null)
    {

        $d = Common::extractDate($date);
        $startDate = Common::dmY2Ymd($d[0]);
        $endDate = Common::dmY2Ymd($d[1]);

        $query = User::with(['quotations' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('quotations_date', [$startDate, $endDate]);
        }, 'quotations.customer', 'quotations.customer.city', 'quotations.customer.companyName'])
            ->where('role', User::EMPLOYEE_ROLE);

        if (!empty($userId)) {
            $query = $query->where('id', $userId);
            return $query->first();
        } else {
            return $query->get();
        }
    }

    public function getOrdersCreated($date, $userId = null)
    {

        $d = Common::extractDate($date);
        $startDate = Common::dmY2Ymd($d[0]);
        $endDate = Common::dmY2Ymd($d[1]);

        $query = User::with(['orders' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate]);
        }, 'orders.customer', 'orders.customer.city', 'orders.customer.companyName'])
            ->where('role', User::EMPLOYEE_ROLE);

        if (!empty($userId)) {
            $query = $query->where('id', $userId);
            return $query->first();
        } else {
            return $query->get();
        }
    }

    // TODO:  LIST DATA =====
    public function getStatus($addAll = false)
    {
        $data = [];

        if ($addAll) {
            $data = ['0' => 'Chọn trạng thái'];
        }

        $data[self::ACTIVATE_STATUS] = 'Hoạt Động';
        $data[self::DEACTIVATE_STATUS] = 'Tạm Khóa';

        return $data;
    }

    public function getListRoles($addAll = false)
    {
        $data = [];

        if ($addAll) {
            $data = ['0' => 'Tất cả'];
        }

        $data[self::ADMIN_ROLE] = 'Quản Trị Viên';
        $data[self::EMPLOYEE_ROLE] = 'Nhân Viên Kinh Doanh';

        return $data;
    }

    // TODO:  FORMAT =====
    public function formatStatus()
    {
        $arr = $this->getStatus();

        switch ($this->status) {
            case self::ACTIVATE_STATUS:
                $output = $arr[self::ACTIVATE_STATUS];
                $cls = 'btn-success';
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

    public function formatRolesText()
    {
        $arr = $this->getListRoles(false);
        if (isset($arr[$this->role])) {
            return $arr[$this->role];
        }
        return '';
    }

    public function generatePassword()
    {
        $this->password = Hash::make($this->password);
    }

    public function eChartGenerateData($data)
    {
        if (!$data || isset($data['name']) || isset($data['value'])) {
            return \GuzzleHttp\json_encode([
                'label' => ['không có dữ liệu'],
                'data' => [
                    ['không có dữ liệu' => 0]
                ]
            ]);
        }
        $label = [];
        foreach ($data as $item):
            $label[] = $item->name;
        endforeach;
        return \GuzzleHttp\json_encode([
            'label' => $label,
            'data' => $data,
        ]);
    }
}
