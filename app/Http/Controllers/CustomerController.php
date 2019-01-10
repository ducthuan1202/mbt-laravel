<?php

namespace App\Http\Controllers;

use App\Care;
use App\City;
use App\Company;
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
            'user' => null,
            'company' => null,
            'date' => null,
        ];
        $searchParams = array_merge($searchParams, $request->all());

        // get dropdown list
        $userModel = new User();
        $companyModel = new Company();
        $model = new Customer();

        $shared = [
            'data' => $model->search($searchParams),
            'hasBuy' => $model->countHasBuy($searchParams),
            'searchParams' => $searchParams,
            'users' => $userModel->getDropDownList(true),
            'companies' => $companyModel->getDropDownList(true),
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
        $companyModel = new Company();

        $model = new Customer();
        $model->average_sale = 0;
        $model->position = 'Nhân viên';

        $userLogin = Auth::user();
        if ($userLogin && isset($userLogin->id)) {
            $model->user_id = $userLogin->id;
        }

        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
            'cities' => $cityModel->getDropDownList(),
            'companies' => $companyModel->getDropDownList(),
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

        $model->checkBeforeSave();
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
        $companyModel = new Company();

        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
            'cities' => $cityModel->getDropDownList(),
            'companies' => $companyModel->getDropDownList(),
            'status' => $model->getStatus()
        ];
        return view('customer.edit', $shared);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
        $debtModel->customer_id = $model->id;

        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
            'cities' => $cityModel->getDropDownList(),
            'status' => $model->getStatus(),
            'orders' => $orderModel->listByUser(),
            'cares' => $careModel->listByUser(),
            'priceQuotations' => $priceQuotationModel->listByUser(),
            'debts' => $debtModel->listByUser(),
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
        $model->checkBeforeSave();
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
        $this->authorize('admin');
        # find model and delete
        $model = $this->finById($id);

        # check in price quotations
        $priceQuotationModel = new PriceQuotation();
        if ($priceQuotationModel->checkCustomerExist($model->id)) {
            return response()->json([
                'success' => false,
                'message' => Messages::DELETE_FAIL_BECAUSE_HAS_RELATIONSHIP_WITH . ' BÁO GIÁ.',
            ]);
        }

        # check in debt
        $debtModel = new Debt();
        if ($debtModel->checkCustomerExist($model->id)) {
            return response()->json([
                'success' => false,
                'message' => Messages::DELETE_FAIL_BECAUSE_HAS_RELATIONSHIP_WITH . ' CÔNG NỢ.',
            ]);
        }

        # check in order
        $orderModel = new Order();
        if ($orderModel->checkCustomerExist($model->id)) {
            return response()->json([
                'success' => false,
                'message' => Messages::DELETE_FAIL_BECAUSE_HAS_RELATIONSHIP_WITH . ' ĐƠN HÀNG.',
            ]);
        }

        # check in care
        $careModel = new Care();
        if ($careModel->checkCustomerExist($model->id)) {
            return response()->json([
                'success' => false,
                'message' => Messages::DELETE_FAIL_BECAUSE_HAS_RELATIONSHIP_WITH . ' CSKH.',
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

        $customerQuery = Customer::with(['companyName'])->select('id', 'name', 'mobile', 'company_id');

        if (!empty($userId)) {
            $customerQuery = $customerQuery->where('user_id', $userId);
        }
        if (!empty($cityId)) {
            $customerQuery = $customerQuery->where('city_id', $cityId);
        }

        $customers = $customerQuery->get();

        $shared = [
            'customers' => $customers,
            'customerId' => $customerId,
        ];
        $output = [
            'success' => true,
            'message' => view('customer.ajax.by_city', $shared)->render()
        ];
        return response()->json($output);
    }

    public function getCitiesByUser(Request $request)
    {
        $userId = $request->get('userId');
        $cityId = $request->get('cityId');

        $customerQuery = Customer::select('id', 'name', 'city_id');

        $userLogin = $this->getUserLogin();
        if($userLogin->role !== User::ADMIN_ROLE){
            $customerQuery = $customerQuery->where('user_id', $userLogin->id);
        }

        if (!empty($userId)) {
            $customerQuery = $customerQuery->where('user_id', $userId);
        }

        $customers = $customerQuery->get()->toArray();
        $customerGroupByCity = collect($customers)->groupBy('city_id');

        $citiesId = [];
        foreach ($customers as $customer) {
            if (!in_array($customer['city_id'], $citiesId)) {
                $citiesId[] = $customer['city_id'];
            }
        }

        if (!empty($citiesId)) {
            $cities = City::select('id', 'name')
                ->whereIn('id', $citiesId)
                ->get()->toArray();
        } else {
            $cities = [];
        }

        $shared = [
            'customers' => $customers,
            'cities' => $cities,
            'customerGroupByCity' => $customerGroupByCity,
            'cityId' => $cityId,
        ];
        $output = [
            'success' => true,
            'data'=>[
                'city_id'=>$cityId,
                'user_id'=>$userId,
            ],
            'message' => view('customer.ajax.city_smart', $shared)->render()
        ];
        return response()->json($output);
    }
}
