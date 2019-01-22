<?php

namespace App\Http\Controllers;

use App\Debt;
use App\Helpers\Common;
use App\Helpers\Messages;
use App\Order;
use App\PaymentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $model->type = PaymentSchedule::ORDER_TYPE;

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
         * @var $debt Debt
         */
        $order = Order::findOrFail($orderId);
        $model = new PaymentSchedule();

        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());

        $model->order_id = $order->id;
        $model->payment_date = Common::dmY2Ymd($model->payment_date);

        $output = [
            'success' => false,
            'message' => Messages::UPDATE_ERROR
        ];

        if ($model->save()) {
            $totalHasPay = DB::table('payment_schedules')
                ->select(DB::raw('SUM(money) AS total'))
                ->where('order_id', $order->id)
                ->where('status', PaymentSchedule::PAID_STATUS)
                ->first();

            $debt = Debt::where('order_id', $order->id)
                ->where('customer_id', $order->customer_id)
                ->first();

            if (!$debt) {
                $debt = new Debt();
                $debt->order_id = $order->id;
                $debt->customer_id = $order->customer_id;
                $debt->status = Debt::NEW_STATUS;
                $debt->type = Debt::NOT_PAY_TYPE;
                $debt->date_create = date('Y-m-d');
            }
            $debt->total_money = $order->getTotalMoneyWithoutPayment() - $totalHasPay->total;

            if ($debt->save()) {
                $output = [
                    'success' => true,
                    'message' => Messages::UPDATE_SUCCESS
                ];
            }
        }
        return response()->json($output);
    }

    public function storeDebt(Request $request, $debtId)
    {

        /**
         * @var $order Order
         * @var $debt Debt
         */
        $debt = Debt::findOrFail($debtId);
        $model = new PaymentSchedule();

        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());

        $model->order_id = $debt->id;
        $model->payment_date = Common::dmY2Ymd($model->payment_date);

        $output = [
            'success' => false,
            'message' => Messages::UPDATE_ERROR
        ];

        if ($model->save()) {
            $output = [
                'success' => true,
                'message' => Messages::UPDATE_SUCCESS
            ];
        }
        return response()->json($output);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
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

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveForm(Request $request, $id)
    {
        /**
         * @var $model PaymentSchedule
         * @var $debt Debt
         * @var $order Order
         */
        $model = PaymentSchedule::findOrFail($id);
        $order = Order::findOrFail($model->order_id);

        $oldData = $model->getOriginal();
        $rules = $model->validateRules;
        unset($rules['order_id']);

        $this->validate($request, $rules, $model->validateMessage);
        $model->fill($request->all());
        $model->payment_date = Common::dmY2Ymd($model->payment_date);

        $output = [
            'success' => false,
            'message' => Messages::UPDATE_ERROR,
        ];

        if ($model->save()) {
            $totalHasPay = DB::table('payment_schedules')
                ->select(DB::raw('SUM(money) AS total'))
                ->where('order_id', $order->id)
                ->where('status', PaymentSchedule::PAID_STATUS)
                ->first();

            $debt = Debt::where('order_id', $order->id)
                ->where('customer_id', $order->customer_id)
                ->first();

            if (!$debt) {
                $debt = new Debt();
                $debt->order_id = $order->id;
                $debt->customer_id = $order->customer_id;
                $debt->status = Debt::NEW_STATUS;
                $debt->type = Debt::NOT_PAY_TYPE;
                $debt->date_create = date('Y-m-d');
            }

            $debt->total_money = $order->getTotalMoneyWithoutPayment() - $totalHasPay->total;

            if ($debt->save()) {
                $output = [
                    'success' => true,
                    'message' => Messages::UPDATE_SUCCESS
                ];
            } else {
                $model->fill($oldData);
                $model->save();
            }
        }
        return response()->json($output);
    }

    public function deleteForm($id)
    {
        /**
         * @var $model PaymentSchedule
         * @var $debt Debt
         * @var $order Order
         */
        $model = PaymentSchedule::findOrFail($id);
        $order = Order::findOrFail($model->order_id);

        $output = [
            'success' => false,
            'message' => Messages::DELETE_ERROR
        ];

        if ($model->delete()) {
            $totalHasPay = DB::table('payment_schedules')
                ->select(DB::raw('SUM(money) AS total'))
                ->where('order_id', $order->id)
                ->where('status', PaymentSchedule::PAID_STATUS)
                ->first();

            $debt = Debt::where('order_id', $order->id)
                ->where('customer_id', $order->customer_id)
                ->first();

            if ($debt) {
                $debt->total_money = $order->getTotalMoneyWithoutPayment() - $totalHasPay->total;
                $debt->save();

            }

            $output = [
                'success' => true,
                'message' => Messages::DELETE_ERROR
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
