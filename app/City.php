<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property string name
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property Company company
 *
 */
class City extends Model
{
    const LIMIT = 10;

    protected $table = 'cities';
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

    public function company()
    {
        return $this->belongsTo(Company::class, 'city_id', 'id');
    }

    public function search($searchParams = [])
    {
        $model = $this;
        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }

        // order by id desc
        $model->orderBy('id', 'desc');

        return $model->paginate(self::LIMIT);
    }

    public function getListCities($addAll = true){
        $data =  $this->select('id', 'name')->get()->toArray();

        if ($addAll) {
            $firstItem = ['id' => null, 'name' => 'Tất cả'];
            array_unshift($data, $firstItem);
        }
        return $data;
    }

}
