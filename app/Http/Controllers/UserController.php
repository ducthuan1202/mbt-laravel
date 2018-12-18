<?php

namespace App\Http\Controllers;

use App\Helpers\Messages;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $model = new User();
        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'roles' => $model->getListRoles(),
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
        $model = new User();
        $shared = [
            "model" => $model,
            'roles' => $model->getListRoles(),
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
            "model" => $model,
            'roles' => $model->getListRoles(),
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
            'role' => 'required',
        ], $model->validateMessage);

        $model->fill($request->all());
        $model->checkBeforeSave();
        $model->save();
        return redirect()
            ->route('users.index')
            ->with('success', Messages::UPDATE_SUCCESS);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePassword()
    {
        $user = Auth::user();
        $model = $this->finById($user->id);

        $shared = [
            "model" => $model,
        ];
        return view('user.change-password', $shared);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $model = $this->finById($user->id);

        $this->validate($request, [
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Mật khẩu không thể bỏ trống.',
            'password.min' => 'Mật khẩu tối thiểu phải chứa 6 ký tự.',
            'password.confirmed' => 'Mật khẩu và xác nhận mật khẩu không trùng nhau.',
        ]);

        if (Hash::check($request->get('old_password'), $model->password)) {
            $model->password = $request->get('password');
            $model->generatePassword();
            $model->save();
            $request->session()->invalidate();

            return redirect()
                ->route('login')->with('success', 'Đổi mật khẩu thành công, bạn cần đăng nhập lại');
        } else {
            return redirect()
                ->route('users.change_password')
                ->withErrors(['Mật khẩu hiện tại không đúng.']);
        }
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
     * @return User
     */
    protected function finById($id)
    {
        return User::findOrFail($id);
    }
}
