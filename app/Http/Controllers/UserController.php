<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $searchParams = [
            'keyword' => '',
            'role' => '',
        ];
        $searchParams = array_merge($searchParams, $request->all());

        $roleModel = new Role();
        $model = new User();
        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'roles' => $roleModel->getDropDownList(),
            'status' => $model->getStatus(),
        ];
        return view('user.index', $shared);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roleModel = new Role();
        $model = new User();
        $shared = [
            "model" => $model,
            'roles' => $roleModel->getDropDownList(false),
            'status' => $model->getStatus(false),
        ];
        return view('user.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $model = new User();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->checkBeforeSave();
        $model->save();
        return redirect()
            ->route('users.index')
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
        $roleModel = new Role();
        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'roles' => $roleModel->getDropDownList(false),
            'status' => $model->getStatus(false),
        ];
        return view('user.edit', $shared);
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
        $this->validate($request, [
            'name' => 'required|max:255',
            'role_id' => 'required',
            'mobile' => 'required|unique:users,mobile',
        ], $model->validateMessage);

        $model->fill($request->all());
        $model->checkBeforeSave();
        $model->save();
        return redirect()
            ->route('users.index')
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
     * @return User
     */
    protected function finById($id)
    {
        return User::findOrFail($id);
    }
}
