<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangkahKecilChecklist extends Model
{
    protected $table = 'langkah_kecil_checklists';

    protected $fillable = [
        'anak_id',
        'title',
        'items',
        'date',
    ];

    protected $casts = [
        'items' => 'array',
        'date' => 'date',
    ];

    public function anak()
    {
        return $this->belongsTo(LangkahKecilAnak::class, 'anak_id');
    }
}
