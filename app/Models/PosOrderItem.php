<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPosOrderItem
 */
class PosOrderItem extends BaseModel
{
    protected $table = 'pos_order_items';
    protected $primaryKey = 'pos_detail_id';
    public $incrementing = true;

    /**
     * Columns available for filtering.
     */
    public static $filterColumns = [
        'pos_detail_id' => 'Pos Detail Id',
        'pos_order_id' => 'Pos Order Id',
        'pos_detail_product_name' => 'Pos Detail Product Name',
        'pos_detail_quantity' => 'Pos Detail Quantity',
    ];

    /**
     * Columns available for sorting.
     */
    public static $sortColumns = [
        'pos_detail_id',
        'pos_order_id',
        'pos_detail_product_name',
        'pos_detail_quantity',
        'pos_detail_line_total',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pos_detail_id',
        'pos_order_id',
        'pos_detail_product_name',
        'pos_detail_unit_price',
        'pos_detail_quantity',
        'pos_detail_extra_price',
        'pos_detail_variant',
        'pos_detail_note',
        'pos_detail_line_total',
    ];

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'pos_order_id' => 'required|exists:pos_orders,pos_id',
            'pos_detail_product_name' => 'string',
            'pos_detail_unit_price' => 'numeric',
            'pos_detail_quantity' => 'integer|min:1',
            'pos_detail_extra_price' => 'numeric',
            'pos_detail_variant' => 'string|nullable',
            'pos_detail_note' => 'string|nullable',
            'pos_detail_line_total' => 'numeric',
        ];
    }

    public static function field_name()
    {
        return 'pos_detail_product_name';
    }

    /**
     * Get the order that owns the item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(PosOrder::class, 'pos_order_id', 'pos_id');
    }
}
