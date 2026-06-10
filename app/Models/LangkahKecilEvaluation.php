<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LangkahKecilEvaluation extends Model
{
    protected $table = 'langkah_kecil_evaluations';

    protected $fillable = [
        'anak_id',
        'skill_key',
        'skill_title',
        'pilar',
        'points',
        'max_points',
        'notes',
    ];

    public function anak(): BelongsTo
    {
        return $this->belongsTo(LangkahKecilAnak::class, 'anak_id');
    }
}
