<?php

namespace App\Http\Controllers;

use App\Actions\CreateProduct;
use App\Actions\DeleteProduct;
use App\Actions\UpdateProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public $model;
    public static $action;
    public static $template;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return redirect()->action([self::class, 'getTable']);
    }

    public function getTable(Request $request)
    {
        $perPage = in_array($request->input('per_page'), ['5','10','25','50','100']) ? (int)$request->per_page : 25;
        $tables = $this->getData()->paginate($perPage)->withQueryString();

        return $this->views($this->template(), ['tables' => $tables]);
    }

    public function getCreate()
    {
        Gate::authorize('save', $this->model);
        return view($this->template(), $this->share());
    }

    public function postCreate(Request $request)
    {
        Gate::authorize('save', $this->model);
        $response = CreateProduct::run($request);
        return $this->response($response);
    }

    public function getUpdate($id)
    {
        Gate::authorize('save', $this->model);
        return $this->views($this->template(), $this->share([
            'model' => $this->model->findOrFail($id),
        ]));
    }

    public function postUpdate(Request $request, $id)
    {
        Gate::authorize('save', $this->model);
        $response = UpdateProduct::run($request, $id);
        return $this->response($response);
    }

    public function postDelete($id)
    {
        Gate::authorize('delete', $this->model);
        $response = DeleteProduct::run($id);
        return $this->response($response);
    }

    public function postDeleteBulk(Request $request)
    {
        Gate::authorize('delete', $this->model);
        $count = (new DeleteProduct)->handleBulk($request);
        return $this->response($count);
    }
}
