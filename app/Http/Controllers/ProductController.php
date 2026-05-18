<?php

namespace App\Http\Controllers;

use App\Actions\CreateProduct;
use App\Actions\DeleteProduct;
use App\Actions\UpdateProduct;
use App\Http\Requests\GeneralRequest;
use App\Models\Product;

class ProductController extends Controller
{
    public $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return redirect()->action([self::class, 'getTable']);
    }

    public function getTable(GeneralRequest $request)
    {
        $data = $this->getData()->paginate($request->input('per_page', 25))->withQueryString();
        return $this->views($this->template(), [
            'data' => $data,
        ]);
    }

    public function getCreate()
    {
        return $this->views($this->template(), ['model' => null]);
    }

    public function postCreate(GeneralRequest $request)
    {
        $response = CreateProduct::run($request);
        return $this->response($response);
    }

    public function getUpdate($id)
    {
        return $this->views($this->template(), [
            'model' => $this->model->findOrFail($id),
        ]);
    }

    public function postUpdate(GeneralRequest $request, $id)
    {
        $response = UpdateProduct::run($request, $id);
        return $this->response($response);
    }

    public function getDelete($id)
    {
        DeleteProduct::run($id);
        return $this->response();
    }

    public function postDeleteBulk(GeneralRequest $request)
    {
        $count = (new DeleteProduct)->handleBulk($request);
        return $this->response($count);
    }
}
