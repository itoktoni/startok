<?php

namespace App\Http\Controllers;

use App\Concerns\ControllerTrait;
use App\Models\Products;

class ProductsController extends Controller
{
    use ControllerTrait;

    public function __construct(Products $model)
    {
        $this->model = $model::getModel();
    }
}
