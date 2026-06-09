<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LangkahKecilSkillActivity extends Model
{
    protected $table = 'langkah_kecil_skill_activities';

    protected $fillable = [
        'skill_id',
        'title',
        'emoji',
        'feature',
        'date',
        'completed',
    ];

    protected function casts(): array
    {
        return [
            'completed' => 'boolean',
        ];
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(LangkahKecilSkill::class, 'skill_id');
    }
}
