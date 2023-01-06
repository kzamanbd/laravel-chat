<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;
    public $conversationId, $targetUserId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message, $conversationId, $targetUserId)
    {
        $this->message = $message;
        $this->targetUserId = $targetUserId;
        $this->conversationId = $conversationId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("message.$this->conversationId"),
            new Channel("notify.message.$this->targetUserId"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.created';
    }
}
