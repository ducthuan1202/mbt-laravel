<?php

namespace App\Http\Controllers;

use App\City;
use App\Customer;
use App\Helpers\Messages;
use App\Order;
use App\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
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
        $cityModel = new City();
        $userModel = new User();
        $customerModel = new Customer();

        $model = new Order();
        $shared = [
            'model' => $model,
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'users' => $userModel->getDropDownList(true),
            'cities' => $cityModel->getDropDownList(true),
            'customers' => $customerModel->getDropDownList(true),
        ];


        return view('order.index', $shared);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userModel = new User();
        $cityModel = new City();
        $customerModel = new Customer();
        $model = new Order();
        $model->start_date = date('Y-m-d');
        $model->shipped_date = date('Y-m-d', strtotime("+1 month"));
        $model->amount = 1;
        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
            'cities' => $cityModel->getDropDownList(),
            'customers' => $customerModel->getDropDownList(),
        ];
        return view('order.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $model = new Order();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->checkBeforeSave();
        if ($model->save()) {
            $model->code = $model->generateUniqueCode();
            $model->save();
        }
        return redirect()
            ->route('payment-schedules.index', $model->id)
            ->with('success', 'Thêm lịch trình thanh toán cho đơn hàng');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userModel = new User();
        $cityModel = new City();
        $customerModel = new Customer();

        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
            'cities' => $cityModel->getDropDownList(),
            'customers' => $customerModel->getDropDownList(),
        ];
        return view('order.edit', $shared);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $model = $this->finById($id);
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->checkBeforeSave();
        $model->save();
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
