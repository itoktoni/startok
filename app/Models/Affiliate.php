<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $table = 'affiliate';
    protected $primaryKey = 'affiliate_id';
    public $timestamps = false;

    protected $fillable = [
        'affiliate_id_user',
        'affiliate_id_from_user',
        'affiliate_id_payment',
        'affiliate_tipe',
        'affiliate_jumlah',
        'affiliate_payment_jumlah',
        'affiliate_commission_rate',
        'affiliate_catatan',
        'affiliate_status',
        'affiliate_created_at',
        'affiliate_updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'affiliate_id_user');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'affiliate_id_from_user');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'affiliate_id_payment', 'payment_id');
    }
}
