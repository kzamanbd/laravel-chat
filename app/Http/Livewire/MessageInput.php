<?php

namespace App\Http\Livewire;

use App\Models\Message;
use Livewire\Component;
use Illuminate\View\View;
use App\Helpers\Helpers;
use App\Events\MessageCreated;
use App\Models\Conversation;

class MessageInput extends Component
{
    public $conversation, $messageText;

    protected $listeners = [
        'conversationSelected' => 'conversationSelected',
    ];

    public function conversationSelected($conversationId)
    {
        $this->conversation = Conversation::find($conversationId);
    }
    /**
     * @return void
     */
    public function sendMessage(): void
    {
        $this->validate([
            'messageText' => 'required'
        ]);

        $messageText = preg_replace(Helpers::LINK_REGEX, Helpers::LINK_REPLACE, $this->messageText);
        $messageText = preg_replace(Helpers::EMAIL_REGEX, Helpers::EMAIL_REPLACE, $messageText);
        $messageText = preg_replace(Helpers::PHONE_REGEX, Helpers::PHONE_REPLACE, $messageText);

        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'user_id' => auth()->id(),
            'message' => $messageText
        ]);

        $to_user_id = $this->conversation->to_user_id;
        $from_user_id = $this->conversation->from_user_id;
        $targetUserId = $to_user_id == auth()->id() ? $from_user_id : $to_user_id;
        $this->conversation->update(['updated_at' => now()]);

        broadcast(new MessageCreated($message, $this->conversation->id, $targetUserId))->toOthers();
        $this->messageText = null;

        $this->emit('userConversationSelected', $this->conversation->id);
        $this->emit('refreshConversationList');
    }
    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.message-input');
    }
}
