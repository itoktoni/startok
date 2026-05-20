<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

class Product extends BaseModel
{
    protected $table   = 'products';
    protected $primaryKey = 'id';

    public $timestamps = true;
    public $incrementing = true;

    /**
     * Columns available for filtering.
     */
    public static $filterColumns = [
        'id' => 'Code',
        'name' => 'Nama',
        'price' => 'Harga',
        'created_at',
        'updated_at',
    ];

    /**
     * Columns available for sorting.
     */
    public static $sortColumns = [
        'id',
        'name',
        'price',
        'description',
        'created_at',
        'updated_at',
    ];

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
