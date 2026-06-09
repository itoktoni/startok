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
        'skills',
        'history',
        'completed_skills',
        'settings',
    ];

    protected $casts = [
        'skills' => 'array',
        'history' => 'array',
        'completed_skills' => 'array',
        'settings' => 'array',
    ];

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
