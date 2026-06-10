<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangkahKecilChallengeHistory extends Model
{
    protected $table = 'langkah_kecil_challenge_history';

    protected $fillable = [
        'anak_id',
        'category',
        'title',
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
