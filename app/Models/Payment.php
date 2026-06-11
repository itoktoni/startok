<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    protected $fillable = [
        'payment_id_user',
        'payment_id_plan',
        'payment_order_code',
        'payment_jumlah',
        'payment_diskon',
        'payment_diskon_code',
        'payment_total',
        'payment_qris_string',
        'payment_status',
        'payment_metode',
        'payment_paid_at',
        'payment_expired_at',
        'payment_created_at',
        'payment_updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'payment_id_user');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'payment_id_plan', 'plan_id');
    }

    public function isPending()
    {
        return $this->payment_status === 'pending' && \Carbon\Carbon::parse($this->payment_expired_at)->isFuture();
    }

    public static function generateCode()
    {
        return 'PAY' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
    }
}
