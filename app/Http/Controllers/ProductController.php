<?php

namespace App\Http\Controllers;

use App\Actions\CreateProduct;
use App\Actions\DeleteProduct;
use App\Actions\UpdateProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return redirect()->action([self::class, 'getTable']);
    }

    private function share(array $data = [])
    {
        return array_merge(['model' => null], $data);
    }

    public function getTable(Request $request)
    {
        $perPage = in_array($request->input('per_page'), ['5','10','25','50','100']) ? (int)$request->per_page : 25;
        $tables = Product::filter()->sort()->paginate($perPage)->withQueryString();

        return $this->respondView('product.table', ['tables' => $tables]);
    }

    public function getData()
    {
        return response()->json(Product::all());
    }

    public function getCreate()
    {
        return view('product.form', $this->share());
    }

    public function postCreate(Request $request)
    {
        $product = CreateProduct::run($request);
        return $this->respond('Product created successfully.', redirect()->action([self::class, 'getTable']), $product, 201);
    }

    public function getUpdate($id)
    {
        return $this->respondView('product.form', $this->share([
            'model' => Product::findOrFail($id),
        ]));
    }

    public function postUpdate(Request $request, $id)
    {
        $product = UpdateProduct::run($request, $id);
        return $this->respond('Product updated successfully.', redirect()->action([self::class, 'getTable']), $product);
    }

    public function postDelete($id)
    {
        DeleteProduct::run($id);
        return $this->respond('Product deleted successfully.', redirect()->action([self::class, 'getTable']));
    }

    public function postDeleteBulk(Request $request)
    {
        $count = (new DeleteProduct)->handleBulk($request);
        return $this->respond($count . ' product(s) deleted.', redirect()->action([self::class, 'getTable']));
    }
}
