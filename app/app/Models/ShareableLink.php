<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareableLink extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'task_id',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public static function generateToken()
    {
        return \Illuminate\Support\Str::random(60);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}
