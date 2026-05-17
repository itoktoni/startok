<?php

namespace App\Actions;

use App\Models\Product;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteProduct
{
    use AsAction;

    public function handle(int $id): void
    {
        Product::findOrFail($id)->delete();
    }

    public function handleBulk(Request $request): int
    {
        $data = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);
        return Product::whereIn('id', $data['ids'])->delete();
    }
}
