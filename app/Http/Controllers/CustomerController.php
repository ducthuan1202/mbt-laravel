<?php

namespace App\Http\Controllers;

use App\City;
use App\Company;
use App\Customer;
use Illuminate\Http\Request;

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
            'company' => null,
            'keyword' => null,
            'buy' => null,
        ];
        $searchParams = array_merge($searchParams, $request->all());

        // get cities
        $cityModel = new City();
        $companyModel = new Company();
        $model = new Customer();
        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'cities' => $cityModel->getDropDownList(),
            'companies' => $companyModel->getDropDownList(),
            'buyStatus' => $model->getBuyStatus()
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
        $cityModel = new City();
        $companyModel = new Company();
        $model = new Customer();
        $model->total_sale = 0;

        $shared = [
            "model" => $model,
            'cities' => $cityModel->getDropDownList(),
            'companies' => $companyModel->getDropDownList(),
            'buyStatus' => $model->getBuyStatus(),
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
        $model->status = Customer::ACTIVATE_STATUS;
        $model->save();
        return redirect()
            ->route('customers.index')
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
        $cityModel = new City();
        $companyModel = new Company();
        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'cities' => $cityModel->getDropDownList(),
            'companies' => $companyModel->getDropDownList(),
            'buyStatus' => $model->getBuyStatus()
        ];
        return view('customer.edit', $shared);
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
     * @return Customer
     */
    protected function finById($id)
    {
        return Customer::findOrFail($id);
    }
}
