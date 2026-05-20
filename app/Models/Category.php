<?php

namespace App\Models;

use App\Models\BaseModel;

class Category extends BaseModel
{
    protected $table = 'category';
    protected $keyType = 'int';
    protected $primaryKey = 'category_id';

    public $timestamps = false;
    public $incrementing = true;

    /**
     * Columns available for filtering.
     */
    public static $filterColumns = [
        'category_id' => 'Category Id',
        'category_nama' => 'Category Nama',
        'category_keterangan' => 'Category Keterangan'
    ];

    /**
     * Columns available for sorting.
     */
    public static $sortColumns = [
        'category_id',
        'category_nama',
        'category_keterangan'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['category_id', 'category_nama', 'category_keterangan'];

}
