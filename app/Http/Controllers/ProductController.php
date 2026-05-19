<?php

namespace App\Http\Controllers;

use App\Actions\CreateAction;
use App\Actions\DeleteAction;
use App\Actions\UpdateAction;
use App\Concerns\PayloadTrait;
use App\Http\Requests\GeneralRequest;
use App\Models\Product;

class ProductController extends Controller
{
    use PayloadTrait;

    public $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return redirect()->action([self::class, 'getTable']);
    }

    public function getShow($id)
    {
        try {
            return $this->payload(TOAST_SUCCESS, $this->model->findOrFail($id));
        } catch (\Throwable $th) {
            return $this->payload(TOAST_FAILED, $th->getMessage());
        }
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
        $response = CreateAction::run($request, $this->model);
        return $this->response($response);
    }

    public function getUpdate($id)
    {
        $model = $this->getShow($id);
        return $this->views($this->template(), [
            'model' => $model['status'] ? $model['data'] : null,
        ]);
    }

    public function postUpdate(GeneralRequest $request, $id)
    {
        $response = UpdateAction::run($request, $id, $this->model);
        return $this->response($response);
    }

    public function getDelete($id)
    {
        $response = (new DeleteAction)->remove($id, $this->model);
        return $this->response($response);
    }

    public function postDeleteBulk(GeneralRequest $request)
    {
        $count = DeleteAction::run($request, $this->model);
        return $this->response($count);
    }
}
