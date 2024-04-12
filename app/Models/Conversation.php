<?php

namespace App\Models;

use App\Http\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = [
        'participant',
        'is_active',
        'msg_preview',
        'last_msg_at',
        'last_active_at'
    ];

    public function fromUser(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'from_user_id');
    }

    public function toUser(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'to_user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id')
            ->orderBy('created_at');
    }

    public function unreadMessage(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id')
            ->where('user_id', '!=', auth()->id())
            ->where('is_seen', 0);
    }

    public function getParticipantAttribute()
    {
        return $this->to_user_id == auth()->id()
            ? $this->fromUser
            : $this->toUser;
    }

    public function getIsActiveAttribute(): bool
    {
        $key = $this->to_user_id == auth()->id()
            ? $this->from_user_id
            : $this->to_user_id;

        return cache()->has("is_active$key");
    }

    public function getLastActiveAtAttribute()
    {
        $key = $this->to_user_id == auth()->id()
            ? $this->from_user_id
            : $this->to_user_id;
        return Helpers::getLastActiveAt($key);
    }

    public function getLastMessage()
    {
        return $this->messages->last();
    }

    public function getMsgPreviewAttribute(): string
    {
        $lastMessage = $this->getLastMessage();
        if ($lastMessage) {
            // remove html tags
            $message = strip_tags($lastMessage->message);
            if ($lastMessage->user_id == auth()->id()) {
                return "You: $message";
            } else {
                return $message;
            }
        }
        return 'No message yet';
    }

    public function getLastMsgAtAttribute(): string
    {
        $message = $this->getLastMessage();
        if ($message) {
            return $message->last_msg_at;
        }
        return 'N/A';
    }
}
