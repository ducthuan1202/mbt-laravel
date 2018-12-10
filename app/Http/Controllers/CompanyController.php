<?php

namespace App\Http\Controllers;

use App\City;
use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
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
        ];
        $searchParams = array_merge($searchParams, $request->all());

        // get cities
        $cityModel = new City();
        $model = new Company();
        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'cities'=>$cityModel->getListCities()
        ];
        return view('company.index', $shared);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cityModel = new City();
        $model = new Company();
        $shared = [
            "model" => $model,
            'cities'=>$cityModel->getListCities()
        ];
        return view('company.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $model = new Company();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->city_id = $request->get('city_id');
        $model->name = $request->get('name');
        $model->desc = $request->get('desc');
        $model->save();
        return redirect()->route('companies.index')->with('success', 'Thêm mới thành công');
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
        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'cities'=>$cityModel->getListCities()
        ];
        return view('company.edit', $shared);
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
        $model->city_id = $request->get('city_id');
        $model->name = $request->get('name');
        $model->desc = $request->get('desc');
        $model->save();
        return redirect()->route('companies.index')->with('success', 'Cập nhật thành công');
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
     * @return Company
     */
    protected function finById($id)
    {
        return Company::findOrFail($id);
    }
}
