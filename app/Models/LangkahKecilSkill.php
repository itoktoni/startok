<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LangkahKecilSkill extends Model
{
    protected $table = 'langkah_kecil_skills';

    protected $fillable = [
        'anak_id',
        'key',
        'emoji',
        'title',
        'pilar',
        'progress',
        'color',
    ];

    public function anak(): BelongsTo
    {
        return $this->belongsTo(LangkahKecilAnak::class, 'anak_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LangkahKecilSkillActivity::class, 'skill_id');
    }
}
