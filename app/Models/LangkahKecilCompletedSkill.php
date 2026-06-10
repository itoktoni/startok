<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LangkahKecilCompletedSkill extends Model
{
    protected $table = 'langkah_kecil_completed_skills';

    protected $fillable = [
        'anak_id',
        'key',
        'emoji',
        'title',
        'pilar',
        'color',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function anak(): BelongsTo
    {
        return $this->belongsTo(LangkahKecilAnak::class, 'anak_id');
    }
}
