<?php

namespace App\Models;

class PosItem extends BaseModel
{
    protected $table = 'pos_items';

    protected $keyType = 'int';

    protected $primaryKey = 'pos_items_id';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'pos_items_id',
        'pos_id',
        'product_id',
        'product_nama',
        'product_harga',
        'pos_qty',
        'pos_item_total',
        'pos_item_note',
    ];
}
