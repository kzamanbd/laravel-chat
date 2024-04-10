<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = [
        'username',
        'avatar_path',
        'last_seen_time',
        'last_message_time'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'seen_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }

    public function getLastSeenTimeAttribute()
    {
        return $this->seen_at
            ? $this->seen_at->diffForHumans()
            : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getUserNameAttribute(): string
    {
        return $this->user->name;
    }
    public function getAvatarPathAttribute(): string
    {
        return $this->user->avatar_path;
    }
    public function getLastMessageTimeAttribute(): string
    {
        $createdAt = $this->created_at;
        // get date week name
        $date = $createdAt->format('Y-m-d');
        $week = $createdAt->format('l');
        $time = $createdAt->format('h:i A');

        if ($date == date('Y-m-d')) {
            return $time;
        } else if ($date == date('Y-m-d', strtotime('-1 day'))) {
            return "Yesterday $time";
        } else if ($date > date('Y-m-d', strtotime('-1 week'))) {
            return "$week $time";
        } else {
            return $createdAt->format('d M Y');
        }
    }
}
