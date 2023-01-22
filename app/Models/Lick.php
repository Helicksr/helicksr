<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lick extends Model
{
    use HasFactory;

    protected $casts = [
        'tags' => 'array',
        'amp_settings' => AsArrayObject::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
