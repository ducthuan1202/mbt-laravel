<?php

namespace App\Http\Controllers;

use App\Customer;
use App\PriceQuotation;
use App\Product;
use Illuminate\Http\Request;

class PriceQuotationController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $searchParams = [
            'customer' => null,
            'product' => null,
            'keyword' => null,
            'date' => null,
            'status' => null,
        ];
        $searchParams = array_merge($searchParams, $request->all());

        // get relation
        $productModel = new Product();
        $customerModel = new Customer();

        $model = new PriceQuotation();
        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'products'=>$productModel->getDropDownList(),
            'customers'=>$customerModel->getDropDownList(),
            'customerStatus'=>$model->getCustomerStatus(),
        ];
        return view('quotation.index', $shared);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productModel = new Product();
        $customerModel = new Customer();

        $model = new PriceQuotation();
        $model->amount = 1;
        $model->total_money = 0;

        $shared = [
            "model" => $model,
            'products'=>$productModel->getDropDownList(),
            'customers'=>$customerModel->getDropDownList(),
            'customerStatus'=>$model->getCustomerStatus(),
        ];
        return view('quotation.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $model = new PriceQuotation();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->total_money = (int) $model->price * (int) $model->amount;
        $model->status = PriceQuotation::ACTIVATE_STATUS;
        $model->save();
        return redirect()
            ->route('quotations.index')
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
        $productModel = new Product();
        $customerModel = new Customer();

        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'products'=>$productModel->getDropDownList(),
            'customers'=>$customerModel->getDropDownList(),
            'customerStatus'=>$model->getCustomerStatus(),
        ];
        return view('quotation.edit', $shared);
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
        $model->total_money = (int) $model->price * (int) $model->amount;
        $model->save();
        return redirect()
            ->route('quotations.index')
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
     * @return PriceQuotation
     */
    protected function finById($id)
    {
        return PriceQuotation::findOrFail($id);
    }
}
