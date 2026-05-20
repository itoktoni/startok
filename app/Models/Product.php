<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

class Product extends BaseModel
{
    protected $table   = 'products';
    protected $keyType = 'int';
    protected $primaryKey = 'id';

    public $timestamps = true;
    public $incrementing = true;

    /**
     * Columns available for filtering.
     */
    public static $filterColumns = [
        'name' => 'Name',
        'id' => 'Id',
        'price' => 'Price',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
    ];

    /**
     * Columns available for sorting.
     */
    public static $sortColumns = [
        'name',
        'price',
        'description',
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

    public static function field_name()
    {
        return 'name';
    }
}
