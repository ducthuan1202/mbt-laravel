<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property integer product_skin_id
 * @property string capacity
 * @property string voltage_input
 * @property string voltage_output
 * @property string price
 * @property string standard
 * @property string status
 *
 * @property string created_at
 * @property string updated_at
 *
 *
 */
class Product extends Model
{
    const LIMIT = 10;
    const ACTIVATE_STATUS = 'activate',
        DEACTIVATE_STATUS = 'deactivate';

    protected $table = 'products';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_skin_id','capacity','voltage_input','voltage_output','price','standard', 'status'];

    public $validateMessage = [
        'product_skin_id.required' => 'tên không thể bỏ trống.',
        'capacity.required' => 'tên không thể bỏ trống.',
        'voltage_input.required' => 'tên không thể bỏ trống.',
        'voltage_output.required' => 'tên không thể bỏ trống.',
        'price.required' => 'tên không thể bỏ trống.',
        'standard.required' => 'tên không thể bỏ trống.',
    ];

    public $validateRules = [
        'product_skin_id' => 'required|max:11',
        'capacity' => 'required|max:255',
        'name' => 'required|max:255',
        'voltage_input' => 'required|max:255',
        'voltage_output' => 'required|max:255',
        'price' => 'required|max:255',
        'standard' => 'required|max:255',
    ];

    public function skin(){
        return $this->hasOne(ProductSkin::class, 'id', 'product_skin_id');
    }

    public function search($searchParams = [])
    {
        $model = $this->with(['skin']);
        $model = $model->orderBy('id', 'desc');
        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }

        return $model->paginate(self::LIMIT);
    }

}
