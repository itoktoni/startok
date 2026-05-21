<?php

namespace App\Models;

use App\Models\BaseModel;

class Product extends BaseModel
{
    protected $table = 'product';
    protected $keyType = 'int';
    protected $primaryKey = 'product_id';

    public $timestamps = false;
    public $incrementing = true;

    /**
     * Columns available for filtering.
     */
    public static $filterColumns = [
        'product_id' => 'Product Id',
        'product_nama' => 'Product Nama',
        'product_harga' => 'Product Harga',
        'product_keterangan' => 'Product Keterangan',
        'product_category_id' => 'Product Category Id'
    ];

    /**
     * Columns available for sorting.
     */
    public static $sortColumns = [
        'product_nama',
        'product_harga',
        'product_keterangan',
        'product_category_id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'product_nama',
        'product_harga',
        'product_keterangan',
        'product_category_id'
    ];

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

			'product_id' => 'required',
			'product_nama' => 'required|string',
			'product_keterangan' => 'string',
        ];
    }

    public function toArray(){}

    public static function field_name()
    {
        return 'product_nama';
    }

}
