<?php

namespace App\Http\Controllers;

use App\Concerns\ControllerTrait;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    use ControllerTrait;

    public function __construct(Product $model)
    {
        $this->model = $model::getModel();
    }

    protected function share($data = [])
    {
        $default = [
            'model' => $this->model,
            'category' => Category::getOptions()
        ];

        return array_merge($default, $data);
    }

    protected function getData()
    {
        $data = $this->model
            ->addSelect(Product::getTableName().'.*', Category::field_name())
            ->leftJoinRelationship('has_category')
            ->orderBy(Product::field_primary(), 'DESC')
            ->filter()->sort();

        return $data;
    }
}
