<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['user_avatar', 'is_online'];

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

    public function getUserAvatarAttribute(): string
    {
        $name = $this->to->id == auth()->id() ? urlencode($this->from->name) : urlencode($this->to->name);
        return "https://ui-avatars.com/api/?background=random&name=$name";
    }

    public function getIsOnlineAttribute(): bool
    {
        return cache()->has('user-activity-' . $this->from_user_id);
    }
}
