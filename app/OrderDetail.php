<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class OrderDetail
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property string order_id
 * @property string product_id
 * @property string amount
 * @property string price
 * @property string total_money
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property Order order
 * @property Product product
 *
 */
class OrderDetail extends Model
{
    const LIMIT = 10;

    protected $table = 'order_details';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id', 'product_id', 'amount', 'price', 'total_money'];

    public $validateMessage = [
        'order_id.required' => 'Chọn khách hàng không thể bỏ trống.',
        'product_id.required' => 'Nội dung cuộc chăm sóc không thể bỏ trống.',
        'amount.required' => 'Chọn ngày chăm sóc.',
        'price.required' => 'Trạng thái không thể bỏ trống.',
        'total_money.required' => 'Trạng thái không thể bỏ trống.',
    ];

    public $validateRules = [
        'order_id' => 'required',
        'product_id' => 'required',
        'amount' => 'required',
        'price' => 'required',
        'total_money' => 'required',
    ];

    public function checkBeforeSave()
    {
        $this->total_money = (int)$this->amount * (int)$this->product_id;
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function search($searchParams = [])
    {
        $model = $this->with(['order', 'product']);
        $model = $model->orderBy('id', 'desc');
        return $model->paginate(self::LIMIT);
    }

    public function formatProduct()
    {
        if ($this->product) {
            return $this->product->name;
        }
        return 'không xác định';
    }

}
