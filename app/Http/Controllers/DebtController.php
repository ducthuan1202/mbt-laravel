<?php

namespace App\Http\Controllers;

use App\City;
use App\Customer;
use App\Debt;
use App\Helpers\Messages;
use App\User;
use Illuminate\Http\Request;

class DebtController extends Controller
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
            'order' => null,
            'customer' => null,
            'user' => null,
            'city' => null,
            'status' => null,
            'type' => null,
        ];
        $searchParams = array_merge($searchParams, $request->all());

        $cityModel = new City();
        $userModel = new User();
        $customerModel = new Customer();
        $model = new Debt();

        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'users' => $userModel->getDropDownList(true),
            'cities' => $cityModel->getDropDownList(true),
            'customers' => $customerModel->getDropDownList(true),
            'status' => $model->listStatus(true),
            'types' => $model->listType(true),
        ];

        return view('debt.index', $shared);
    }

    public function oldDebt(Request $request)
    {
        $this->authorize('admin');
        $searchParams = [
            'order' => null,
            'customer' => null,
            'user' => null,
            'city' => null,
            'status' => Debt::OLD_STATUS,
            'type' => null,
        ];
        $searchParams = array_merge($searchParams, $request->all());

        $cityModel = new City();
        $userModel = new User();
        $customerModel = new Customer();
        $model = new Debt();

        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'users' => $userModel->getDropDownList(true),
            'cities' => $cityModel->getDropDownList(true),
            'customers' => $customerModel->getDropDownList(true),
            'status' => $model->listStatus(true),
            'types' => $model->listType(true),
        ];

        return view('debt.index', $shared);
    }

    public function newDebt(Request $request)
    {
        $this->authorize('admin');
        $searchParams = [
            'order' => null,
            'customer' => null,
            'user' => null,
            'city' => null,
            'status' => Debt::NEW_STATUS,
            'type' => null,
        ];
        $searchParams = array_merge($searchParams, $request->all());

        $cityModel = new City();
        $userModel = new User();
        $customerModel = new Customer();
        $model = new Debt();

        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'users' => $userModel->getDropDownList(true),
            'cities' => $cityModel->getDropDownList(true),
            'customers' => $customerModel->getDropDownList(true),
            'status' => $model->listStatus(true),
            'types' => $model->listType(true),
        ];

        return view('debt.index', $shared);
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('admin');
        $cityModel = new City();
        $userModel = new User();

        $model = new Debt();
        $shared = [
            "model" => $model,
            'cities' => $cityModel->getDropDownList(),
            'users' => $userModel->getDropDownList(),
        ];
        return view('debt.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->authorize('admin');
        $model = new Debt();
        $this->validate($request, [
            'user_id' => 'required|integer|min:1',
            'customer_id' => 'required|integer|min:1',
            'total_money' => 'required|integer|min:1',
        ], $model->validateMessage);
        $model->fill($request->all());
        $model->checkBeforeSave();
        $model->save();
        return redirect()
            ->route('debts.index')
            ->with('success', Messages::INSERT_SUCCESS);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $this->authorize('admin');
        $cityModel = new City();
        $userModel = new User();
        $model = $this->finById($id);

        $shared = [
            "model" => $model,
            'cities' => $cityModel->getDropDownList(),
            'users' => $userModel->getDropDownList(),
        ];
        return view('debt.edit', $shared);
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
        $this->authorize('admin');
        $model = $this->finById($id);
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->checkBeforeSave();
        $model->save();
        return redirect()
            ->route('debts.index')
            ->with('success', Messages::UPDATE_SUCCESS);
    }

    /**
     * @param $id
     * @return Debt
     */
    protected function finById($id)
    {
        return Debt::findOrFail($id);
    }
}
