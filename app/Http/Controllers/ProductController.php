<?php

namespace App\Http\Controllers;

use App\Concerns\ControllerTrait;
use App\Models\Product;

class ProductController extends Controller
{
    use ControllerTrait;

    public function __construct(Product $model)
    {
        $this->model = $model::getModel();
    }
}
