<?php

namespace App\Http\Controllers;

use App\City;
use App\Company;
use App\Customer;
use App\Debt;
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
            'date' => null,
            'user' => null,
            'customer' => null,
            'status' => null,
        ];
        $searchParams = array_merge($searchParams, $request->all());

        $userModel = new User();
        $customerModel = new Customer();
        $model = new Debt();

        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'users' => $userModel->getDropDownList(),
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
        $userModel = new User();
        $customerModel = new Customer();

        $model = new Debt();

        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
            'customers' => $customerModel->getDropDownList(),
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
        $model = new Customer();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->checkBeforeSave();

        if($model->save()){
            $model->code = $model->generateUniqueCode($model->id);
            $model->save();
        }

        return redirect()
            ->route('debts.index')
            ->with('success', 'Thêm mới thành công');
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
        $customerModel = new Customer();

        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'users' => $userModel->getDropDownList(),
            'customers' => $customerModel->getDropDownList(),
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
        $model->checkBeforeSave();

        $model->save();
        return redirect()
            ->route('debts.index')
            ->with('success', 'Cập nhật thành công');
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
                'message' => 'Xóa thành công.'
            ];
        } else {
            $output = [
                'success' => false,
                'message' => 'Xóa thất bại',
            ];
        }
        return response()->json($output);
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
