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

    protected $table = 'care_histories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['care_id', 'start_date', 'end_date', 'content', 'customer_note', 'status'];

    public $validateMessage = [
        'care_id.required' => 'Chọn khách hàng.',
        'start_date.required' => 'Ngày bắt đầu không thể bỏ trống.',
        'end_date.required' => 'Ngày gọi lại không thể bỏ trống.',
        'content.required' => 'Nội dung chăm sóc không thể bỏ trống.',
        'customer_note.required' => 'Ghi chú khách hàng không thể bỏ trống.',
        'status.required' => 'Trạng thái không thể bỏ trống.',
    ];

    public $validateRules = [
        'care_id' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
        'content' => 'required',
        'customer_note' => 'required',
        'status' => 'required',
    ];

    public function care()
    {
        return $this->belongsTo(Care::class, 'care_id', 'id');
    }

    public function search()
    {
        $model = $this->orderBy('start_date', 'desc');
        if (!empty($this->care_id)) {
            $model = $model->where('care_id', $this->care_id);
        }
        return $model->get();
    }

    public function getStatus($addAll = true)
    {
        $data = [];
        if ($addAll) {
            $data = [null => 'Tất cả'];
        }
        $data = array_merge($data, [
            self::ACTIVATE_STATUS => 'Kích Hoạt',
            self::DEACTIVATE_STATUS => 'Tạm Hoãn',
        ]);

        return $data;
    }

    public function checkBeforeSave()
    {
        if (!empty($this->start_date)) {
            $this->start_date = $this->dmyToymd($this->start_date);
        }
        if (!empty($this->end_date)) {
            $this->end_date = $this->dmyToymd($this->end_date);
        }
    }

    public function formatDate($time)
    {
        return date('d/m/Y', strtotime($time));
    }

    public function formatStartDate()
    {
        return $this->formatDate($this->start_date);
    }

    public function formatEndDate()
    {
        return $this->formatDate($this->end_date);
    }

    public function dmyToymd($date)
    {
        $date = str_replace('/', '-', $date);
        return date('Y-m-d', strtotime($date));
    }

    public function formatStatus()
    {
        $arr = $this->getStatus();
        switch ($this->status) {
            case self::ACTIVATE_STATUS:
                $output = $arr[self::ACTIVATE_STATUS];
                $cls = 'btn-info';
                break;
            case  self::DEACTIVATE_STATUS:
                $output = $arr[self::DEACTIVATE_STATUS];
                $cls = 'btn-default';
                break;
            default:
                $output = 'n/a';
                $cls = 'btn-default';
                break;
        }
        return sprintf('<span class="btn btn-xs btn-round %s" style="width: 80px">%s</span>', $cls, $output);
    }
}
