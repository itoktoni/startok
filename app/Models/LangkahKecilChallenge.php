<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangkahKecilChallenge extends Model
{
    protected $table = 'langkah_kecil_challenges';

    protected $fillable = [
        'anak_id',
        'category',
        'title',
        'emoji',
        'points',
        'status',
        'date',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'date' => 'date',
    ];

    public function anak()
    {
        return $this->belongsTo(LangkahKecilAnak::class, 'anak_id');
    }
}
