<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Company
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property string name
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property Customer $customers
 */
class Company extends Model
{
    const LIMIT = 50;

    protected $table = 'companies';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public $validateMessage = [
        'name.required' => 'tên không thể bỏ trống.',
        'name.max' => 'tên tối đa 255 ký tự',
    ];

    public $validateRules = [
        'name' => 'required|max:255',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'company_id', 'id');
    }

    // TODO:  QUERY TO DATABASE =====
    public function search($searchParams = [])
    {
        $model = $this->orderBy('id', 'desc');
        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }
        return $model->paginate(self::LIMIT);
    }

    public static function countNumber()
    {
        return self::count();
    }

    public function getDropDownList($addAll = false)
    {
        $data = $this->select('id', 'name')->get()->toArray();

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

}
