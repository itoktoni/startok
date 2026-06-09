<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangkahKecilSchedule extends Model
{
    protected $table = 'langkah_kecil_schedules';

    protected $fillable = [
        'anak_id',
        'label',
        'time',
        'done',
        'date',
    ];

    protected $casts = [
        'done' => 'boolean',
        'date' => 'date',
    ];

    public function anak()
    {
        return $this->belongsTo(LangkahKecilAnak::class, 'anak_id');
    }
}
