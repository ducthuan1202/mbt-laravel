<?php

namespace App\Http\Controllers;

use App\City;
use App\Customer;
use App\Helpers\Messages;
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

        # check in debt
        $customerModel = new Customer();
        if ($customerModel->checkCityExist($model->id)) {
            return response()->json([
                'success' => false,
                'message' => Messages::DELETE_FAIL_BECAUSE_HAS_RELATIONSHIP_WITH.' KHÁCH HÀNG.',
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
     * @return City
     */
    protected function finById($id)
    {
        return City::findOrFail($id);
    }

}
