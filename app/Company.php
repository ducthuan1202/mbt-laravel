<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Company
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property integer city_id
 * @property string name
 * @property string desc
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property City city
 *
 */
class Company extends Model
{
    const LIMIT = 10;

    protected $table = 'companies';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'city_id', 'desc'];

    public $validateMessage = [
        'city_id.required' => 'khu vực không thể bỏ trống.',
        'name.required' => 'tên không thể bỏ trống.',
        'name.max' => 'tên tối đa 255 ký tự',
        'desc.required' => 'mô tả không thể bỏ trống.',
    ];

    public $validateRules = [
        'city_id' => 'required|max:255',
        'name' => 'required|max:255',
        'desc' => 'required|max:255',
    ];

    // relationship with city
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    // search data
    public function search($searchParams = [])
    {
        $model = $this->with(['city']);
        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }
        // filter by city
        if (isset($searchParams['city']) && !empty($searchParams['city'])) {
            $model = $model->where('city_id', $searchParams['city']);
        }

        // order by id desc
        $model = $model->orderBy('id', 'desc');

        return $model->paginate(self::LIMIT);
    }

    public static function countNumber()
    {
        return self::count();
    }

    public function getDropDownList($addAll = true)
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

    public function formatCity()
    {
        if ($this->city) {
            return $this->city->name;
        }
        return 'không xác định';
    }

}
