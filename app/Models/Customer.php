<?php

namespace App\Models;

use App\Models\BaseModel;

class Customer extends BaseModel
{
    protected $table = 'customer';
    protected $keyType = 'int';
    protected $primaryKey = 'customer_id';

    public $timestamps = false;
    public $incrementing = true;

    /**
     * Columns available for filtering.
     */
    public static $filterColumns = [
        'customer_id' => 'Id',
        'customer_nama' => 'Nama',
        'customer_phone' => 'Phone',
        'customer_address' => 'Address'
    ];

    /**
     * Columns available for sorting.
     */
    public static $sortColumns = [
        'customer_id',
        'customer_nama',
        'customer_phone',
        'customer_address'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'customer_nama',
        'customer_phone',
        'customer_address'
    ];

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
			'customer_nama' => 'required|string',
			'customer_phone' => 'string',
			'customer_address' => 'string',
        ];
    }

    public function toArray(){}

    public static function field_name()
    {
        return 'customer_nama';
    }

}
