<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Psr\SimpleCache\InvalidArgumentException;

class Conversation extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = [
        'username',
        'user_avatar',
        'is_online',
        'latest_message',
        'latest_message_time',
        'unread_message_count',
    ];

    // get user data
    public function getUserData()
    {
        $userId = $this->to_user_id == auth()->id() ? 'from_user_id' : 'to_user_id';
        return $this->hasOne(User::class, 'id', $userId);
    }

    public function from(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'from_user_id');
    }

    public function to(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'to_user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }

    public function unreadMessage(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id')
            ->where('user_id', '!=', auth()->id())
            ->where('is_seen', 0);
    }

    public function getUserInfo()
    {
        return $this->to_user_id == auth()->id()
            ? $this->from
            : $this->to;
    }

    public function getUsernameAttribute(): string
    {
        return $this->getUserInfo()->name;
    }

    public function getUserAvatarAttribute(): string
    {
        $name = urlencode($this->getUserInfo()->name);
        return "https://ui-avatars.com/api/?background=d5d3f8&color=7269ef&name=$name";
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getIsOnlineAttribute(): bool
    {
        $key = $this->to_user_id == auth()->id()
            ? $this->from_user_id
            : $this->to_user_id;

        return cache()->has("is_online$key");
    }

    public function getLatestMessageAttribute(): string
    {
        if ($this->messages->last()) {
            // remove html tags
            $message = strip_tags($this->messages->last()->message);
            if ($this->messages->last()->user_id == auth()->id()) {
                return "You: $message";
            } else {
                return $message;
            }
        }
        return 'No message yet';
    }

    public function getLatestMessageTimeAttribute(): string
    {
        if ($this->messages->last()) {
            return $this->messages->last()->created_at->diffForHumans() ?? '';
        }
        return '';
    }

    public function getUnreadMessageCountAttribute(): int
    {
        return $this->unreadMessage->count();
    }
}
