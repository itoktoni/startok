<?php

namespace App\Models;

use App\Models\BaseModel;

class Products extends BaseModel
{
    protected $table = 'products';
    protected $keyType = 'int';
    protected $primaryKey = 'products_id';

    public $timestamps = false;
    public $incrementing = true;

    /**
     * Columns available for filtering.
     */
    public static $filterColumns = [
        
    ];

    /**
     * Columns available for sorting.
     */
    public static $sortColumns = [
        
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        
    ];

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            
        ];
    }

    public function toArray(){}

    public static function field_name()
    {
        return 'products_nama';
    }

}
