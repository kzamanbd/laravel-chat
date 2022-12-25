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
        'latest_message_time'
    ];

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

    public function getUserName()
    {
        return $this->to_user_id == auth()->id()
            ? $this->from->name
            : $this->to->name;
    }

    public function getUsernameAttribute(): string
    {
        return $this->getUserName();
    }

    public function getUserAvatarAttribute(): string
    {
        $name = urlencode($this->getUserName());
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

        return cache()->has("is-online-$key");
    }

    public function getLatestMessageAttribute(): string
    {
        return $this->messages->last()->message ?? '';
    }

    public function getLatestMessageTimeAttribute(): string
    {
        return $this->messages->last()->created_at->diffForHumans() ?? '';
    }
}
