<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CareHistory
 * @package App
 * -------------------------------------
 * @property integer id
 *
 * @property integer care_id
 *
 * @property string start_date
 * @property string end_date
 * @property string content
 * @property string customer_note
 * @property string status
 *
 * @property string created_at
 * @property string updated_at
 *
 * @property Care care
 *
 */
class CareHistory extends Model
{
    const LIMIT = 10;
    const ACTIVATE_STATUS = 'activate',
        DEACTIVATE_STATUS = 'deactivate';

    protected $table = 'cares';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'customer_id', 'content', 'call_date', 'status'];

    public $validateMessage = [
        'customer_id.required' => 'Chọn khách hàng không thể bỏ trống.',
        'content.required' => 'Nội dung cuộc chăm sóc không thể bỏ trống.',
        'call_date.required' => 'Chọn ngày chăm sóc.',
        'status.required' => 'Trạng thái không thể bỏ trống.',
    ];

    public $validateRules = [
        'customer_id' => 'required',
        'content' => 'required',
        'call_date' => 'required',
        'status' => 'required',
    ];

    public function care()
    {
        return $this->belongsTo(Care::class, 'care_id', 'id');
    }

    public function search($searchParams = [])
    {
        $model = $this->orderBy('id', 'desc');
        return $model->get();
    }
}
