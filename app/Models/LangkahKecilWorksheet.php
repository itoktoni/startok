<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangkahKecilWorksheet extends Model
{
    protected $table = 'langkah_kecil_worksheets';

    protected $fillable = [
        'anak_id',
        'type',
        'data',
        'date',
    ];

    protected $casts = [
        'data' => 'array',
        'date' => 'date',
    ];

    public function anak()
    {
        return $this->belongsTo(LangkahKecilAnak::class, 'anak_id');
    }
}
