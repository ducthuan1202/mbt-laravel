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
 *
 * @property string created_at
 * @property string updated_at
 *
 */
class Role extends Model
{
    const LIMIT = 10;

    protected $table = 'roles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'desc'];

    public $validateMessage = [
        'name.required' => 'tên không thể bỏ trống.',
        'name.max' => 'tên tối đa 255 ký tự',
        'desc.required' => 'mô tả không thể bỏ trống.',
    ];

    public $validateRules = [
        'name' => 'required|max:255',
        'desc' => 'required',
    ];

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

}
