<?php

namespace App\Http\Controllers;

use App\Care;
use App\City;
use App\Customer;
use App\Debt;
use App\Helpers\Messages;
use App\Order;
use App\PriceQuotation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $searchParams = [
            'city' => null,
            'keyword' => null,
            'status' => null,
        ];
        $searchParams = array_merge($searchParams, $request->all());

        // get dropdown list
        $userModel = new User();
        $cityModel = new City();
        $model = new Customer();

        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'users' => $userModel->getDropDownList(true),
            'cities' => $cityModel->getDropDownList(true),
            'status' => $model->getStatus(true)
        ];
        return view('customer.index', $shared);
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

        $model = new Customer();
        $model->average_sale = 0;

        $userLogin = Auth::user();
        if ($userLogin && isset($userLogin->id)) {
            $model->user_id = $userLogin->id;
        }

        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
            'cities' => $cityModel->getDropDownList(),
            'status' => $model->getStatus()
        ];
        return view('customer.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $model = new Customer();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());

        if ($model->save()) {
            $model->code = $model->generateUniqueCode();
            $model->save();
        }

        return redirect()
            ->route('customers.index')
            ->with('success', Messages::INSERT_SUCCESS);
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

        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
            'cities' => $cityModel->getDropDownList(),
            'status' => $model->getStatus()
        ];
        return view('customer.edit', $shared);
    }

    public function show($id)
    {
        $model = $this->finById($id);

        $userModel = new User();
        $cityModel = new City();

        $orderModel = new Order();
        $orderModel->customer_id = $model->id;

        $careModel = new Care();
        $careModel->customer_id = $model->id;

        $priceQuotationModel = new PriceQuotation();
        $priceQuotationModel->customer_id = $model->id;
        $debtModel = new Debt();

        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
            'cities' => $cityModel->getDropDownList(),
            'status' => $model->getStatus(),
            'orders'=>$orderModel->listByUser(),
            'cares'=>$careModel->listByUser(),
            'priceQuotations'=>$priceQuotationModel->listByUser(),
            'debts'=>$debtModel,
        ];
        return view('customer.detail', $shared);
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
        $model->save();
        return redirect()
            ->route('customers.index')
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

        # check in price quotations
        $priceQuotationModel = new PriceQuotation();
        if ($priceQuotationModel->checkCustomerExist($model->id)) {
            return response()->json([
                'success' => false,
                'message' => Messages::DELETE_FAIL_BECAUSE_HAS_RELATIONSHIP_WITH.' BÁO GIÁ.',
            ]);
        }

        # check in debt
        $debtModel = new Debt();
        if ($debtModel->checkCustomerExist($model->id)) {
            return response()->json([
                'success' => false,
                'message' => Messages::DELETE_FAIL_BECAUSE_HAS_RELATIONSHIP_WITH.' CÔNG NỢ.',
            ]);
        }

        # check in order
        $orderModel = new Order();
        if ($orderModel->checkCustomerExist($model->id)) {
            return response()->json([
                'success' => false,
                'message' => Messages::DELETE_FAIL_BECAUSE_HAS_RELATIONSHIP_WITH.' ĐƠN HÀNG.',
            ]);
        }

        # check in care
        $careModel = new Care();
        if ($careModel->checkCustomerExist($model->id)) {
            return response()->json([
                'success' => false,
                'message' => Messages::DELETE_FAIL_BECAUSE_HAS_RELATIONSHIP_WITH.' CSKH.',
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
     * @return Customer
     */
    protected function finById($id)
    {
        return Customer::findOrFail($id);
    }

    // TODO: api
    public function getByCity(Request $request)
    {

        $cityId = (int)$request->get('cityId');
        $userId = (int)$request->get('userId');
        $customerId = (int)$request->get('customerId');

        $model = new Customer();
        $model->city_id = $cityId;
        $model->user_id = $userId;

        $shared = [
            'model' => $model,
            'customers' => $model->getDropDownList(true),
            'customerId' => $customerId,
        ];
        $output = [
            'success' => true,
            'message' => view('customer.ajax.by_city', $shared)->render()
        ];
        return response()->json($output);
    }
}
