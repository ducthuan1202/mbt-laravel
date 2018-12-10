<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductSkin;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $searchParams = [
            'skin' => null,
            'keyword' => null,
        ];
        $searchParams = array_merge($searchParams, $request->all());

        $skinModel = new ProductSkin();
        $model = new Product();

        $shared = [
            'data' => $model->search($searchParams),
            'searchParams' => $searchParams,
            'skins' => $skinModel->getListSkins(),
        ];
        return view('product.index', $shared);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $skinModel = new ProductSkin();
        $model = new Product();
        $shared = [
            "model" => $model,
            'skins' => $skinModel->getListSkins(),
        ];

        return view('product.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $model = new Product();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->capacity = $request->get('capacity');
        $model->status = ProductSkin::ACTIVATE_STATUS;
        $model->save();
        return redirect()
            ->route('products.index')
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
        $skinModel = new ProductSkin();
        $model = $this->finById($id);
        $shared = [
            "model" => $model,
            'skins' => $skinModel->getListSkins(),
        ];
        return view('product.edit', $shared);
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
        $model->save();
        return redirect()
            ->route('products.index')
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
     * @return Product
     */
    protected function finById($id)
    {
        return Product::findOrFail($id);
    }
}
