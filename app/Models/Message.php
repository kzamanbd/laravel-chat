<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['user_avatar', 'last_seen_time'];

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
        return $this->seen_at ? $this->seen_at->diffForHumans() : null;
    }

    public function getUserAvatarAttribute(): string
    {
        return $name = $this->conversation->to_user_id == auth()->id()
            ? urlencode($this->conversation->from->name)
            : urlencode($this->conversation->to->name);

        return "https://ui-avatars.com/api/?background=random&name=$name";;
    }
}
