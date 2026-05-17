<?php

namespace App\Actions;

use App\Models\Product;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProduct
{
    use AsAction;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }

    public function handle(Request $request, int $id): Product
    {
        $data = $request->validate($this->rules());
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }
}
