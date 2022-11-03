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
    protected $appends = ["user_avatar"];

    public function from(): HasOne
    {
        return $this->hasOne(User::class, "id", "from_user_id");
    }

    public function to(): HasOne
    {
        return $this->hasOne(User::class, "id", "to_user_id");
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, "conversation_id", "id");
    }

    public function unread_message(): HasMany
    {
        return $this->hasMany(Message::class, "conversation_id", "id")->where("status", "0");
    }

    public function getUserAvatarAttribute(): string
    {
        $name = $this->to->id == auth()->id() ? $this->from->name : $this->to->name;
        return "https://ui-avatars.com/api/?background=random&name=$name";
    }
}
