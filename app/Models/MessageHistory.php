<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ["send_at"];

    public function getSendAtAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, "message_id", "id");
    }
}
