<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
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

        $model = new Role();
        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams
        ];
        return view('role.index', $shared);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Role();
        $shared = [
            "model" => $model
        ];
        return view('role.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $model = new Role();

        $this->validate($request, $model->validateRules, $model->validateMessage);

        $model->name = $request->get('name');
        $model->desc = $request->get('desc');

        $model->save();
        return redirect()->route('roles.index')->with('success', 'Thêm mới thành công');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $id;
        //
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
        return view('role.edit', $shared);
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

        $model->name = $request->get('name');
        $model->desc = $request->get('desc');

        $model->save();

        return redirect()->route('roles.index')->with('success', 'Cập nhật thành công');
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
        if ($model && $model->delete()) {
            return response()->json(['success' => true,'message' => 'Xóa thành công.']);
        }

        # return default not found.
        return response()->json([
            'success' => false,
            'message' => 'Xóa thất bại',
        ]);
    }

    /**
     * @param $id
     * @return Role
     */
    protected function finById($id)
    {
        return Role::findOrFail($id);
    }
}
