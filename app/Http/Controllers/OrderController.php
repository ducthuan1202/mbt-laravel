<?php

namespace App\Http\Controllers;

use App\City;
use App\Customer;
use App\Debt;
use App\Helpers\Messages;
use App\Order;
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
        $this->authorize('admin');
        $searchParams = [
            'customer' => null,
            'user' => null,
            'city' => null,
            'keyword' => null,
            'date' => null,
            'status' => null,
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

    public function shipped(Request $request)
    {
        $searchParams = [
            'customer' => null,
            'user' => null,
            'city' => null,
            'keyword' => null,
            'date' => null,
            'status' => Order::SHIPPED_STATUS,
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

    public function noShipped(Request $request)
    {
        $searchParams = [
            'customer' => null,
            'user' => null,
            'city' => null,
            'keyword' => null,
            'date' => null,
            'status' => Order::NOT_SHIPPED_STATUS,
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

    public function cancel(Request $request)
    {
        $searchParams = [
            'customer' => null,
            'user' => null,
            'city' => null,
            'keyword' => null,
            'date' => null,
            'status' => Order::CANCEL_STATUS,
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
        if($priceQuotation){
            $model->fill($priceQuotation->getAttributes());
            $model->standard_real = $model->standard_output;
            $model->status = Order::NOT_SHIPPED_STATUS;
            $message = sprintf('Thông tin đơn hàng hiện tại được lấy từ báo giá #%s ', $priceQuotation->code);
        } else {
            $message = sprintf('Không có báo giá phù hợp, đơn hàng phải nhập số liệu từ đầu');
        }

        $model->start_date = date('Y-m-d');
        $model->shipped_date = date('Y-m-d', strtotime("+1 month"));

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
        if($request->get('code')){
            $this->validate($request, ['code' => 'unique:orders,code'], ['code.unique' => 'Đơn hàng cho báo giá này đã tồn tại.']);
        }
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->checkBeforeSave();

        if($model->save()){
            $debt = new Debt();
            $debt->order_id = $model->id;
            $debt->customer_id = $model->customer_id;
            $debt->total_money = 0;
            $debt->status = Debt::NEW_STATUS;
            $debt->type = Debt::NOT_PAY_TYPE;
            $debt->date_create = date('Y-m-d');

            if($model->status == Order::SHIPPED_STATUS){
                $debt->total_money = $model->getTotalMoneyWithoutPayment();
            }

            $debt->save();
            return redirect()
                ->route('orders.index')
                ->with('success', Messages::UPDATE_SUCCESS);
        } else {
            return redirect()
                ->route('orders.index', $model->id)
                ->withErrors(['lỗi khi tạo công nợ']);
        }
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

        if($model->save()){
            $debt = Debt::where('order_id', $model->id)
                ->where('customer_id', $model->customer_id)
                ->first();

            if($model->status == Order::SHIPPED_STATUS){
                if(!$debt) {
                    $debt = new Debt();
                    $debt->order_id = $model->id;
                    $debt->customer_id = $model->customer_id;
                    $debt->status = Debt::NEW_STATUS;
                    $debt->type = Debt::NOT_PAY_TYPE;
                    $debt->date_create = date('Y-m-d');
                    $debt->save();
                } else{
                    $debt->total_money = 0;
                }

                $debt->total_money = $model->total_money - $model->prepay + $model->vat;
                $debt->save();
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
