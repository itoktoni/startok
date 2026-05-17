<?php

namespace App\Actions;

use App\Models\Product;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProduct
{
    use AsAction;

    public function handle(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }
}
