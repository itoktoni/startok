<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use App\Models\BaseModel;

class Product extends BaseModel
{
    protected $table   = 'products';
    protected $primaryKey = 'id';

    public $timestamps = true;
    public $incrementing = true;

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }

    protected $fillable = ['name', 'price', 'description'];

    public function toArray()
    {
        $data = parent::toArray();

        if (Auth::check()) {
            unset($data['price']);
        }

        return $data;
    }
}
