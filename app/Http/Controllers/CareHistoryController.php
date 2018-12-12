<?php

namespace App\Http\Controllers;

use App\CareHistory;
use Illuminate\Http\Request;

class CareHistoryController extends Controller
{
    public function index(Request $request)
    {
        $model = new CareHistory();
        $model->care_id = $request->get('id');

        $shared = [
            'data' => $model->search(),
            'status' => $model->getStatus(false)
        ];
        $output = [
            'success' => true,
            'message' => view('care-history.ajax.index', $shared)->render()
        ];

        return response()->json($output);
    }

    public function create(Request $request)
    {
        $model = new CareHistory();
        $model->care_id = $request->get('id');

        $shared = [
            'model' => $model,
            'status' => $model->getStatus(false)
        ];
        $output = [
            'success' => true,
            'message' => view('care-history.ajax.form', $shared)->render()
        ];

        return response()->json($output);
    }

    public function store(Request $request)
    {
        $model = new CareHistory();
        $this->validate($request, $model->validateRules, $model->validateMessage);

        $model->fill($request->all());
        $model->checkBeforeSave();
        if ($model->save()) {
            $output = [
                'success' => true,
                'message' => 'Thêm mới thành công'
            ];
        } else {
            $output = [
                'success' => false,
                'message' => 'Không thể lưu lịch sử.'
            ];
        }
        return response()->json($output);
    }

}
