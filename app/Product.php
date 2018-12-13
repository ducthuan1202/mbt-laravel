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
 * @property string name
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
 * @property ProductSkin skin
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
    protected $fillable = [
        'product_skin_id', 'name',
        'capacity', 'voltage_input', 'voltage_output',
        'price', 'standard', 'status'
    ];

    public $validateMessage = [
        'name.required' => 'Tên sản phẩm không thể bỏ trống.',
        'product_skin_id.required' => 'Chọn loại hình sản phẩm.',
        'capacity.required' => 'Công suất không thể bỏ trống.',
        'voltage_input.required' => 'Điện áp vào không thể bỏ trống.',
        'voltage_output.required' => 'Điện áp ra không thể bỏ trống.',
        'price.required' => 'Giá bán không thể bỏ trống.',
        'standard.required' => 'Tiêu chuẩn không thể bỏ trống.',
    ];

    public $validateRules = [
        'product_skin_id' => 'required|max:11',
        'capacity' => 'required|max:255',
        'name' => 'required|max:255',
        'voltage_input' => 'required|max:255',
        'voltage_output' => 'required|max:255',
        'price' => 'required|max:11',
        'standard' => 'required|max:255',
    ];

    // relation
    public function skin()
    {
        return $this->hasOne(ProductSkin::class, 'id', 'product_skin_id');
    }

    public function priceQuotation()
    {
        return $this->belongsTo(PriceQuotation::class, 'product_id', 'id');
    }

    // query db
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

    public function search($searchParams = [])
    {
        $model = $this->with(['skin']);
        $model = $model->orderBy('id', 'desc');

        // filter by keyword
        if (isset($searchParams['keyword']) && !empty($searchParams['keyword'])) {
            $model = $model->where('name', 'like', "%{$searchParams['keyword']}%");
        }
        // filter by skin
        if (isset($searchParams['skin']) && !empty($searchParams['skin'])) {
            $model = $model->where('product_skin_id', $searchParams['skin']);
        }

        return $model->paginate(self::LIMIT);
    }

    // format display
    public function formatSkin()
    {
        if ($this->skin) {
            return $this->skin->name;
        }
        return 'không xác định';
    }

    public function formatMoney()
    {
        return number_format($this->price) . ',000 đ';
    }
}
