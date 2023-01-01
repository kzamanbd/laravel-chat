<?php

namespace App\Http\Livewire;

use App\Models\Message;
use Livewire\Component;
use Illuminate\View\View;
use App\Helpers\Constrains;
use App\Events\MessageCreated;

class MessageInput extends Component
{
    public $conversationId, $messageText;

    protected $listeners = [
        'conversationSelected' => 'conversationSelected',
    ];

    public function conversationSelected($conversationId)
    {
        $this->conversationId = $conversationId;
    }
    /**
     * @return void
     */
    public function sendMessage(): void
    {
        $this->validate([
            'messageText' => 'required'
        ]);

        $messageText = preg_replace(Constrains::LINK_REGEX, Constrains::LINK_REPLACE, $this->messageText);
        $messageText = preg_replace(Constrains::EMAIL_REGEX, Constrains::EMAIL_REPLACE, $messageText);
        $messageText = preg_replace(Constrains::PHONE_REGEX, Constrains::PHONE_REPLACE, $messageText);

        $message = Message::create([
            'conversation_id' => $this->conversationId,
            'user_id' => auth()->id(),
            'message' => $messageText
        ]);
        $message->conversation->update(['updated_at' => now()]);
        broadcast(new MessageCreated($message))->toOthers();
        $this->messageText = null;
        $this->emit('userConversationSelected', $this->conversationId);
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
