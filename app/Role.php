<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property string name
 * @property string desc
 * @property string role_group
 *
 * @property string created_at
 * @property string updated_at
 *
 */
class Role extends Model
{
    const LIMIT = 10;
    const
        EMPLOYEE_ROLE = 'employees',
        MANAGER_ROLE = 'manager',
        ADMIN_ROLE = 'admin';

    protected $table = 'roles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'desc', 'role_group'];

    public $validateMessage = [
        'name.required' => 'Tên không thể bỏ trống.',
        'name.max' => 'Tên tối đa 255 ký tự',
        'desc.required' => 'Mô tả không thể bỏ trống.',
        'role_group.required' => 'Chọn nhóm quyền .',
    ];

    public $validateRules = [
        'name' => 'required|max:255',
        'desc' => 'required',
        'role_group' => 'required',
    ];

    public function search($searchParams = [])
    {
        $model = $this->orderBy('id', 'desc');
        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }
        if (isset($searchParams['group']) && !empty($searchParams['group'])) {
            $model = $model->where('role_group', $searchParams['group']);
        }

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

    public function getListRoles($addAll =  true){
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data = array_merge($data, [
            self::EMPLOYEE_ROLE => 'Nhân Viên',
            self::MANAGER_ROLE => 'Quản Lý - Giám Đốc',
            self::ADMIN_ROLE => 'Quản Trị Viên',
        ]);

        return $data;
    }

    public function formatGroupRole()
    {
        $arr = $this->getListRoles();
        switch ($this->role_group){
            case self::EMPLOYEE_ROLE:
                $output = $arr[self::EMPLOYEE_ROLE];
                $cls = 'btn-default';
                break;
            case  self::MANAGER_ROLE:
                $output = $arr[self::MANAGER_ROLE];
                $cls = 'btn-info';
                break;
            case self::ADMIN_ROLE:
                $output = $arr[self::ADMIN_ROLE];
                $cls = 'btn-warning';
                break;
            default:
                $output = 'n/a';
                $cls = 'btn-default';
                break;
        }
        return sprintf('<span class="btn btn-xs btn-round %s" style="width: 120px">%s</span>', $cls, $output);
    }

}
