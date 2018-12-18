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
     */
    public function index(Request $request)
    {
        $searchParams = [
            'order' => null,
            'customer' => null,
            'status' => null,
        ];
        $searchParams = array_merge($searchParams, $request->all());

        $customerModel = new Customer();
        $model = new Debt();

        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'customers' => $customerModel->getDropDownList(),
            'status' => $model->getStatus(),
        ];
        return view('debt.index', $shared);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cityModel = new City();
        $userModel = new User();

        $model = new Debt();
        $shared = [
            "model" => $model,
            'cities' => $cityModel->getDropDownList(),
            'users' => $userModel->getDropDownList(),
            'status' => $model->getStatus(),
        ];
        return view('debt.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $model = new Debt();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->save();
        return redirect()
            ->route('debts.index')
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
        $cityModel = new City();
        $userModel = new User();
        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'cities' => $cityModel->getDropDownList(),
            'users' => $userModel->getDropDownList(),
            'status' => $model->getStatus(),
        ];
        return view('debt.edit', $shared);
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
