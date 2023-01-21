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
    public int $targetUserId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message, int $targetUserId)
    {
        $this->message = $message;
        $this->targetUserId = $targetUserId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("message.{$this->message->conversation_id}"),
            new Channel("notify.message.$this->targetUserId"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'created';
    }
}
