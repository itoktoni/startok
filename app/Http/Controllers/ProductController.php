<?php

namespace App\Http\Controllers;

use App\Actions\CreateProduct;
use App\Actions\UpdateProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ProductController extends Controller
{
    public function __construct()
    {
        View::share('title', 'Product Management');
    }

    private function share($data = [])
    {
        $default = [
            'model' => false
        ];

        return array_merge($default, $data);
    }

    public function index()
    {
        return redirect()->action([self::class, 'getTable']);
    }

    public function getTable(Request $request)
    {
        $perPage = in_array($request->input('per_page'), ['5','10','25','50']) ? (int)$request->per_page : 10;
        $products = Product::filter()->sort()->paginate($perPage)->withQueryString();

        return view('product.table', compact('products'));
    }

    public function getData()
    {
        $products = DB::table('products')->get();
        return response()->json($products);
    }

    public function getCreate()
    {
        return view('product.create', $this->share());
    }

    public function postCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        CreateProduct::run($request->only('name', 'price', 'description'));

        flash()->success('Product created successfully.');

        return redirect()->action([self::class, 'getTable']);
    }

    public function getUpdate($id)
    {
        $product = Product::findOrFail($id);
        return view('product.update', $this->share([
            'product' => $product
        ]));
    }

    public function postUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);
        UpdateProduct::run($product, $request->only('name', 'price', 'description'));

        flash()->success('Product updated successfully.');

        return redirect()->action([self::class, 'getTable']);
    }

    public function getDelete($id)
    {
        $product = Product::findOrFail($id);
        return view('product.delete', compact('product'));
    }

    public function postDelete($id)
    {
        Product::findOrFail($id)->delete();

        flash()->success('Product deleted successfully.');

        return redirect()->action([self::class, 'getTable']);
    }

    public function postDeleteBulk(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);
        Product::whereIn('id', $request->ids)->delete();

        flash()->success(count($request->ids) . ' product(s) deleted.');

        return redirect()->action([self::class, 'getTable']);
    }
}
