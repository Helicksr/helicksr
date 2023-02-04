<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Lick extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'tags' => 'array',
        'amp_settings' => 'array',
    ];

    protected $appends = [
        'audio_file_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usersSharedDirectly()
    {
        return $this->belongsToMany(User::class, 'shared_lick_user');
    }

    public function teamsSharedDirectly()
    {
        return $this->belongsToMany(Team::class, 'shared_lick_team');
    }

    public function getAudioFileUrlAttribute()
    {
        return $this->audio_file_path ? Storage::url($this->audio_file_path) : null;
    }
}
