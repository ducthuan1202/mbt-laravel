<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductSkin
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property string name
 * @property string status
 *
 * @property string created_at
 * @property string updated_at
 *
 */
class ProductSkin extends Model
{
    const LIMIT = 10;
    const ACTIVATE_STATUS = 'activate',
        DEACTIVATE_STATUS = 'deactivate';

    protected $table = 'product_skins';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'status'];

    public $validateMessage = [
        'name.required' => 'tên không thể bỏ trống.',
        'name.max' => 'tên tối đa 255 ký tự',
    ];

    public $validateRules = [
        'name' => 'required|max:255',
    ];

    public function product()
    {
        return $this->belongsTo(Company::class, 'city_id', 'id');
    }

    public function search($searchParams = [])
    {
        $model = $this->orderBy('id', 'desc');
        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }

        return $model->paginate(self::LIMIT);
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

}
