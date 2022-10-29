<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $appends = ["send_at"];
    public function getSendAtAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, "conversation_id", "id");
    }
}
