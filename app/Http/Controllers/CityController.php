<?php

namespace App\Http\Controllers;

use App\City;
use App\Company;
use App\Customer;
use Illuminate\Http\Request;

class CityController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $searchParams = [
            'keyword' => ''
        ];
        $searchParams = array_merge($searchParams, $request->all());

        $model = new City();
        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams
        ];
        return view('city.index', $shared);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new City();
        $shared = [
            "model" => $model
        ];
        return view('city.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $model = new City();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->save();
        return redirect()
            ->route('cities.index')
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
        $model = $this->finById($id);
        $shared = [
            "model" => $model
        ];
        return view('city.edit', $shared);
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
            ->route('cities.index')
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

        # check in debt
        $customerModel = new Customer();
        if ($customerModel->checkCityExist($model->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa do có liên quan tới KHÁCH HÀNG.',
            ]);
        }

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
     * @return City
     */
    protected function finById($id)
    {
        return City::findOrFail($id);
    }

}
