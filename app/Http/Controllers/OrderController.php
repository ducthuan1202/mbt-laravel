<?php

namespace App\Http\Controllers;

use App\City;
use App\Customer;
use App\Debt;
use App\Helpers\Messages;
use App\Order;
use App\PaymentSchedule;
use App\PriceQuotation;
use App\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $searchParams = [
            'customer' => null,
            'user' => null,
            'city' => null,
            'keyword' => null,
            'date' => null,
            'status' => $request->get('status'),
        ];
        $searchParams = array_merge($searchParams, $request->all());

        // get relation
        $userModel = new User();
        $model = new Order();
        $shared = [
            'model' => $model,
            'data' => $model->search($searchParams),
            'count' => $model->countByStatus($searchParams),
            'searchParams' => $searchParams,
            'users' => $userModel->getDropDownList(true),
        ];

        return view('order.index', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request)
    {
        $this->authorize('admin');

        /**
         * @var $priceQuotation PriceQuotation
         */
        $priceQuotation = PriceQuotation::where('id', $request->get('orderId'))
            ->where('status', PriceQuotation::SUCCESS_STATUS)
            ->first();

        $userModel = new User();
        $model = new Order();
        if ($priceQuotation) {
            $model->fill($priceQuotation->getAttributes());
            $model->standard_real = $model->standard_output;
            $message = sprintf('Thông tin đơn hàng hiện tại được lấy từ báo giá #%s ', $priceQuotation->code);
        } else {
            $model->amount = 1;
            $message = sprintf('Không có báo giá phù hợp, đơn hàng phải nhập số liệu từ đầu');
        }

        $model->loadValueCreateDefault();

        $shared = [
            "message" => $message,
            "model" => $model,
            'users' => $userModel->getDropDownList(),
        ];
        return view('order.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        /**
         * @var $debt Debt
         */
        $this->authorize('admin');
        $model = new Order();

        $rules = $model->validateRules;
        if ($request->get('code')) {
            $rules = array_merge($model->validateRules, [
                'code' => 'unique:orders,code',
                'product_number' => 'required|unique:orders,product_number',
            ]);
        }
        $this->validate($request, $rules, $model->validateMessage);
        $model->fill($request->all());
        $model->checkBeforeSave();

        if ($model->save()) {
            if (empty($model->code)) {
                $model->code = $model->generateUniqueCode();
                $model->save();
            }
            $debtModel = new Debt();
            $debtModel->syncWhenUpdateOrder($model);

            // update status for customer if has buy
            if($model->status == Order::SHIPPED_STATUS){
                $customer = new Customer();
                $customer->setStatusHasBuy($model->customer_id);
            }

            return redirect()
                ->route('orders.index')
                ->with('success', Messages::UPDATE_SUCCESS);
        } else {
            return redirect()
                ->route('orders.index', $model->id)
                ->withErrors(['lỗi khi tạo đơn hàng']);
        }
    }

    public function show($id){

        $model = Order::findOrFail($id);
        $this->authorize('view-order', $model);

        $paymentScheduleModel = new PaymentSchedule();
        $paymentScheduleModel->order_id = $model->id;
        $paymentScheduleModel->type = PaymentSchedule::ORDER_TYPE;

        $payments = $paymentScheduleModel->search();
        $shared = [
            "model" => $model,
            "payments" => $payments,
            "paymentSchedule" => $paymentScheduleModel,
        ];
        return view('order.show', $shared);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $this->authorize('admin');
        $userModel = new User();

        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
        ];
        return view('order.edit', $shared);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        /**
         * @var $debt Debt
         */
        $this->authorize('admin');
        $model = $this->finById($id);
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->checkBeforeSave();
        if ($model->save()) {
            if (empty($model->code)) {
                $model->code = $model->generateUniqueCode();
                $model->save();
            }

            $debtModel = new Debt();
            $debtModel->syncWhenUpdateOrder($model);

            // update status for customer if has buy
            if($model->status == Order::SHIPPED_STATUS){
                $customer = new Customer();
                $customer->setStatusHasBuy($model->customer_id);
            }
        }

        return redirect()
            ->route('orders.index')
            ->with('success', Messages::UPDATE_SUCCESS);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('admin');
        # find model and delete
        $model = $this->finById($id);

        # check in debt
        $paymentScheduleModel = new PaymentSchedule();
        if ($paymentScheduleModel->checkOrderExist($model->id)) {
            return response()->json([
                'success' => false,
                'message' => Messages::DELETE_FAIL_BECAUSE_HAS_RELATIONSHIP_WITH . ' LỊCH TRÌNH THANH TOÁN.',
            ]);
        }

        if ($model->delete()) {
            $output = [
                'success' => true,
                'message' => Messages::DELETE_SUCCESS
            ];
        } else {
            $output = [
                'success' => false,
                'message' => Messages::DELETE_ERROR
            ];
        }
        return response()->json($output);
    }

    /**
     * @param $id
     * @return Order
     */
    protected function finById($id)
    {
        return Order::findOrFail($id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function detail(Request $request)
    {
        $this->authorize('admin');
        $id = $request->get('id');
        $model = $this->finById($id);

        $shared = [
            'model' => $model,
        ];
        $output = [
            'success' => true,
            'message' => view('order.ajax.detail', $shared)->render()
        ];
        return response()->json($output);
    }

    /**
     * @param $code
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function detailByCode($code)
    {
        /** @var $model Order */
        $this->authorize('admin');

        $model = Order::where('code', $code)->first();
        if($model){
            return redirect()->route('payment-schedules.index', ['id'=>$model->id]);
        } else {
            return abort(404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function getByCustomer(Request $request)
    {
        $model = new Order();
        $model->customer_id = $request->get('customerId');

        $shared = [
            'orders' => $model->getDropDownList(true),
            'orderId' => $request->get('orderId'),
        ];
        $output = [
            'success' => true,
            'message' => view('order.ajax.by_customer', $shared)->render()
        ];
        return response()->json($output);
    }
}
