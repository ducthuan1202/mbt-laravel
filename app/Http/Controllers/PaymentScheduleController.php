<?php

namespace App\Http\Controllers;

use App\Debt;
use App\Helpers\Common;
use App\Helpers\Messages;
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
        return view('payment-schedule.index', $shared);
    }

    /**
     * @param Request $request
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, $orderId)
    {

        /**
         * @var $order Order
         * @var $debtModel Debt
         */
        $order = Order::findOrFail($orderId);

        $model = new PaymentSchedule();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->order_id = $order->id;
        $model->payment_date = Common::dmY2Ymd($model->payment_date);

        if ($model->save()) {

            // update debt
            $debtModel = Debt::where('order_id', $order->id)
                ->where('customer_id', $order->customer_id)
                ->first();

            if ($model->status == PaymentSchedule::PAID_STATUS) {
                $debtModel->total_money = $debtModel->total_money - $model->money;
                $debtModel->save();
            }

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

    public function openForm($id)
    {

        /**
         * @var $model PaymentSchedule
         * @var $debtModel Debt
         */
        $model = PaymentSchedule::findOrFail($id);
        $shared = [
            'model' => $model,
        ];
        $output = [
            'success' => true,
            'message' => view('payment-schedule.ajax.open_form', $shared)->render()
        ];
        return response()->json($output);
    }

    public function saveForm(Request $request, $id)
    {

        /**
         * @var $model PaymentSchedule
         * @var $debt Debt
         */
        $model = PaymentSchedule::findOrFail($id);
        $oldData = $model->getOriginal();

        $rules = $model->validateRules;
        unset($rules['order_id']);

        $this->validate($request, $rules, $model->validateMessage);
        $model->fill($request->all());
        $model->payment_date = Common::dmY2Ymd($model->payment_date);

        // get debt
        $debt = Debt::where('order_id', $model->order_id)->first();

        if (!$debt) {
            return response()->json([
                'success' => false,
                'message' => Messages::UPDATE_ERROR
            ]);
        }

        $oldMoney = $oldData['money'];
        $newMoney = $model->money;

        if ($model->getOriginal('status') == PaymentSchedule::PAID_STATUS) {
            if ($model->status == PaymentSchedule::PAID_STATUS) {
                // cập nhật lại công nợ
                $debt->total_money = ($debt->total_money - $oldMoney) + $newMoney;
            } else {
                $debt->total_money = $debt->total_money - $oldMoney;
            }
        } else {
            if ($model->status == PaymentSchedule::PAID_STATUS) {
                // cập nhật công nợ
                $debt->total_money = $debt->total_money - $newMoney;
            }
        }

        // update payment schedule
        if ($model->save()) {

            // update debt
            if ($debt->save()) {
                $output = [
                    'success' => true,
                    'message' => Messages::UPDATE_SUCCESS
                ];
            } else {
                // backup data if fail
                $model->fill($oldData);
                $model->save();
                $output = [
                    'success' => false,
                    'message' => Messages::UPDATE_ERROR,
                    'code' => 1
                ];
            }
        } else {
            $output = [
                'success' => false,
                'message' => Messages::UPDATE_ERROR,
                'code' => 2
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
