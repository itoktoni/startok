<?php

namespace App\Actions;

use App\Models\Product;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateProduct
{
    use AsAction;

    public function handle(array $data): Product
    {
        return Product::create($data);
    }
}
