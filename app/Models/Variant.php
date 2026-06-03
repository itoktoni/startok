<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperVariant
 */
class Variant extends BaseModel
{
    protected $table = 'variants';
    protected $primaryKey = 'variant_id';
    public $incrementing = true;

    /**
     * Columns available for filtering.
     */
    public static $filterColumns = [
        'variant_id' => 'Variant Id',
        'product_id' => 'Product Id',
        'variant_nama' => 'Variant Nama',
        'variant_harga' => 'Variant Harga',
    ];

    /**
     * Columns available for sorting.
     */
    public static $sortColumns = [
        'variant_id',
        'product_id',
        'variant_nama',
        'variant_harga',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'variant_id',
        'product_id',
        'variant_nama',
        'variant_harga',
        'variant_deskripsi',
        'variant_active',
    ];

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:product,product_id',
            'variant_nama' => 'required|string',
            'variant_harga' => 'required|numeric',
            'variant_deskripsi' => 'string|nullable',
            'variant_active' => 'boolean',
        ];
    }

    public static function field_name()
    {
        return 'variant_nama';
    }

    /**
     * Get the product that owns the variant.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
