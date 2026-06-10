<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashout extends Model
{
    protected $table = 'cashouts';
    protected $primaryKey = 'cashout_id';
    public $timestamps = false;

    protected $fillable = [
        'cashout_id_user',
        'cashout_jumlah',
        'cashout_admin_fee',
        'cashout_diterima',
        'cashout_rekening_bank',
        'cashout_rekening_nomor',
        'cashout_rekening_nama',
        'cashout_status',
        'cashout_catatan',
        'cashout_created_at',
        'cashout_updated_at',
    ];

    protected $casts = [
        'cashout_jumlah' => 'integer',
        'cashout_admin_fee' => 'integer',
        'cashout_diterima' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'cashout_id_user');
    }
}
