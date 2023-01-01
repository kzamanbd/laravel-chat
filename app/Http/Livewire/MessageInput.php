<?php

namespace App\Http\Livewire;

use App\Models\Message;
use Livewire\Component;
use Illuminate\View\View;
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

        $message = Message::create([
            'conversation_id' => $this->conversationId,
            'user_id' => auth()->id(),
            'message' => $this->messageText
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
