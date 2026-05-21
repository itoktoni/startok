<?php

namespace App\Models;

class Pos extends BaseModel
{
    protected $table = 'pos';

    protected $keyType = 'int';

    protected $primaryKey = 'pos_id';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'pos_id',
        'pos_no',
        'pos_total',
        'pos_payment',
        'pos_change',
        'pos_payment_method',
        'pos_keterangan',
        'pos_created_at',
    ];

    public static $filterColumns = [
        'pos_id' => 'POS Id',
        'pos_no' => 'POS No',
        'pos_total' => 'Total',
        'pos_payment_method' => 'Payment Method',
        'pos_created_at' => 'Created At',
    ];

    public static $sortColumns = [
        'pos_no',
        'pos_total',
        'pos_payment_method',
        'pos_created_at',
    ];

    public function rules(): array
    {
        return [
            'pos_no' => 'required|string',
            'pos_total' => 'required|numeric',
            'pos_payment' => 'required|numeric',
            'pos_payment_method' => 'required|string',
        ];
    }

    public static function field_name()
    {
        return 'pos_no';
    }

    public function posItems()
    {
        return $this->hasMany(PosItem::class, 'pos_id', 'pos_id');
    }
}
