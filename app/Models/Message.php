<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ["send_at"];

    public function getSendAtAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, "conversation_id", "id");
    }
}
