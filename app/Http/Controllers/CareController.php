<?php

namespace App\Http\Controllers;

use App\Care;
use App\City;
use App\Customer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CareController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $searchParams = [
            'city' => null,
            'user' => null,
            'customer' => null,
            'keyword' => null,
            'status' => null,
            'buy' => null,
            'date' => null,
        ];
        $searchParams = array_merge($searchParams, $request->all());

        $cityModel = new City();
        $userModel = new User();
        $customerModel = new Customer();
        $model = new Care();
        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'users'=>$userModel->getDropDownList(true),
            'cities'=>$cityModel->getDropDownList(true),
            'customers'=>$customerModel->getDropDownList(true),
            'status' => $model->listStatus(),
            'buyStatus' => $customerModel->getStatus(true),
        ];

        return view('care.index', $shared);
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

        $model = new Care();
        $model->start_date = date('Y-m-d');
        $model->end_date = date('Y-m-d');

        $userLogin = Auth::user();
        if($userLogin && isset($userLogin->id)){
            $model->user_id = $userLogin->id;
        }

        $shared = [
            "model" => $model,
            'users'=>$userModel->getDropDownList(),
            'cities'=>$cityModel->getDropDownList(),
            'status' => $model->listStatus()
        ];
        return view('care.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $model = new Care();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->checkBeforeSave();
        $model->save();
        return redirect()
            ->route('cares.index')
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
        $cityModel = new City();

        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'cities'=>$cityModel->getDropDownList(),
            'users'=>$userModel->getDropDownList(),
            'status' => $model->listStatus()
        ];

        return view('care.edit', $shared);
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
            ->route('cares.index')
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
     * @return Care
     */
    protected function finById($id)
    {
        return Care::findOrFail($id);
    }
}
