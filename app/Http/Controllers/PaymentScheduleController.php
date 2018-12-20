<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use App\Order;
use App\PaymentSchedule;
use Illuminate\Http\Request;

class PaymentScheduleController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * @param $orderId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($orderId)
    {
        $order = Order::findOrFail($orderId);
        $model = new PaymentSchedule();
        $model->order_id = $order->id;

        $shared = [
            "model" => $model,
            "data" => $model->search(),
            "order" => $order,
        ];
        return view('payment-schedule.create', $shared);
    }

    /**
     * @param Request $request
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, $orderId)
    {

        $order = Order::findOrFail($orderId);
        $model = new PaymentSchedule();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->order_id = $order->id;
        $model->payment_date = Common::dmY2Ymd($model->payment_date);
        if ($model->save()) {
            $output = [
                'success' => true,
                'message' => 'thành công'
            ];
        } else {
            $output = [
                'success' => false,
                'message' => 'thất bại'
            ];
        }
        return response()->json($output);
    }

    /**
     * @param $id
     * @return PaymentSchedule
     */
    protected function finById($id)
    {
        return PaymentSchedule::findOrFail($id);
    }

}
