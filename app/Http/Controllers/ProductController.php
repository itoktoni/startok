<?php

namespace App\Http\Controllers;

use App\Actions\CreateProduct;
use App\Actions\DeleteProduct;
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
        return response()->json(DB::table('products')->get());
    }

    public function getCreate()
    {
        return view('product.form');
    }

    public function postCreate(Request $request)
    {
        CreateProduct::run($request);
        flash()->success('Product created successfully.');
        return redirect()->action([self::class, 'getTable']);
    }

    public function getUpdate($id)
    {
        return view('product.form', ['product' => Product::findOrFail($id)]);
    }

    public function postUpdate(Request $request, $id)
    {
        UpdateProduct::run($request, $id);
        flash()->success('Product updated successfully.');
        return redirect()->action([self::class, 'getTable']);
    }

    public function postDelete($id)
    {
        DeleteProduct::run($id);
        flash()->success('Product deleted successfully.');
        return redirect()->action([self::class, 'getTable']);
    }

    public function postDeleteBulk(Request $request)
    {
        $count = (new DeleteProduct)->handleBulk($request);
        flash()->success($count . ' product(s) deleted.');
        return redirect()->action([self::class, 'getTable']);
    }
}
