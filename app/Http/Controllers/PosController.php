<?php

namespace App\Http\Controllers;

use App\Concerns\ControllerTrait;
use App\Models\Pos;

class PosController extends Controller
{
    use ControllerTrait;

    public function __construct(Pos $model)
    {
        $this->model = $model::getModel();
    }

    public function index()
    {
        return view('pages.pos.index');
    }

    public function getTable()
    {
        $data = $this->model
            ->with(['posItems'])
            ->orderBy('pos_id', 'DESC')
            ->cursorPaginate(25)
            ->withQueryString();

        return $this->views('pos.table', [
            'data' => $data,
            'fields' => $this->getFields(),
        ]);
    }

    public function getData()
    {
        return $this->model->with(['posItems'])->orderBy('pos_id', 'DESC');
    }

    protected function getFields()
    {
        $fields = [];
        if (property_exists($this->model, 'filterColumns') && ! empty($this->model::$filterColumns)) {
            foreach ($this->model::$filterColumns as $key => $value) {
                if ($value === false || $value === null || $value === '' || is_int($key)) {
                    continue;
                }
                if (is_numeric($key)) {
                    $fields[$value] = ucwords(str_replace('_', ' ', $value));
                } else {
                    $fields[$key] = $value;
                }
            }
        }

        return $fields;
    }

    public function getCreate()
    {
        return $this->views('pos.form');
    }
}
