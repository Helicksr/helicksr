<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lick extends Model
{
    use HasFactory;

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

    public function getAudioFileUrlAttribute()
    {
        return $this->audio_file_path ? Storage::url($this->audio_file_path) : null;
    }
}
