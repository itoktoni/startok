<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangkahKecilAnak extends Model
{
    protected $table = 'langkah_kecil_anak';

    protected $fillable = [
        'user_id',
        'nama',
        'gender',
        'umur',
        'tanggal_lahir',
        'bulan_lahir',
        'tahun_lahir',
        'emoji',
        'avatar',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function skills()
    {
        return $this->hasMany(LangkahKecilSkill::class, 'anak_id');
    }

    public function completedSkills()
    {
        return $this->hasMany(LangkahKecilCompletedSkill::class, 'anak_id');
    }

    public function challenges()
    {
        return $this->hasMany(LangkahKecilChallenge::class, 'anak_id');
    }

    public function challengeHistory()
    {
        return $this->hasMany(LangkahKecilChallengeHistory::class, 'anak_id');
    }

    public function checklists()
    {
        return $this->hasMany(LangkahKecilChecklist::class, 'anak_id');
    }

    public function schedules()
    {
        return $this->hasMany(LangkahKecilSchedule::class, 'anak_id');
    }

    public function worksheets()
    {
        return $this->hasMany(LangkahKecilWorksheet::class, 'anak_id');
    }
}
