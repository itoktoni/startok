<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperPosOrder
 */
class PosOrder extends BaseModel
{
    protected $table = 'pos_orders';
    protected $primaryKey = 'pos_id';
    public $incrementing = true;

    /**
     * Columns available for filtering.
     */
    public static $filterColumns = [
        'pos_id' => 'Pos Id',
        'pos_order_code' => 'Pos Order Code',
        'pos_payment_method' => 'Pos Payment Method',
        'pos_total' => 'Pos Total',
        'pos_status' => 'Pos Status',
    ];

    /**
     * Columns available for sorting.
     */
    public static $sortColumns = [
        'pos_id',
        'pos_order_code',
        'pos_payment_method',
        'pos_total',
        'pos_status',
        'created_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pos_id',
        'pos_order_code',
        'pos_payment_method',
        'pos_subtotal',
        'pos_discount',
        'pos_tax',
        'pos_shipping_cost',
        'pos_total',
        'pos_shipping_type',
        'pos_shipping_address',
        'pos_shipping_lat',
        'pos_shipping_lng',
        'pos_voucher_code',
        'pos_voucher_discount',
        'pos_status',
        'pos_notes',
    ];

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'pos_order_code' => 'string|unique:pos_orders,pos_order_code',
            'pos_payment_method' => 'string|in:cash,qris,cod',
            'pos_subtotal' => 'numeric',
            'pos_discount' => 'numeric',
            'pos_tax' => 'numeric',
            'pos_shipping_cost' => 'numeric',
            'pos_total' => 'numeric',
            'pos_shipping_type' => 'string|in:cod_berbah,cod_piyungan,delivery',
            'pos_shipping_address' => 'string|nullable',
            'pos_voucher_code' => 'string|nullable',
            'pos_voucher_discount' => 'numeric',
            'pos_status' => 'string|in:pending,completed,cancelled',
        ];
    }

    public static function field_name()
    {
        return 'pos_order_code';
    }

    /**
     * Get the order items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PosOrderItem::class, 'pos_order_id', 'pos_id');
    }

    /**
     * Generate unique order code.
     */
    public static function generateCode(): string
    {
        $prefix = 'POS';
        $date = now()->format('Ymd');
        $random = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        return "{$prefix}{$date}{$random}";
    }
}
