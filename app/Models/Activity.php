<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'type',
        'title',
        'slug',
        'desc',
        'image',
        'moral',
        'ages',
        'skills',
        'data',
        'sort_order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'ages' => 'array',
            'skills' => 'array',
            'data' => 'array',
            'active' => 'boolean',
        ];
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
