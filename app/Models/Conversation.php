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
        'is_active',
        'msg_preview',
        'last_msg_at',
        'last_active_at'
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id')
            ->orderBy('created_at');
    }

    public function participant(): HasOne
    {
        if ($this->to_user_id == auth()->id()) {
            return $this->hasOne(User::class, 'id', 'from_user_id');
        }
        return $this->hasOne(User::class, 'id', 'to_user_id');
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
        $updatedAt = $this->updated_at;
        // get date week name
        $date = $updatedAt->format('Y-m-d');
        $week = $updatedAt->format('l');
        $time = $updatedAt->format('h:i A');

        if ($date == date('Y-m-d')) {
            return $time;
        } else if ($date == date('Y-m-d', strtotime('-1 day'))) {
            return "Yesterday $time";
        } else if ($date > date('Y-m-d', strtotime('-1 week'))) {
            return "$week $time";
        }
        return $updatedAt->format('d M Y');
    }
}
