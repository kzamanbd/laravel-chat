<?php

namespace App\Http\Livewire;

use App\Models\Conversation;
use Livewire\Component;
use Illuminate\View\View;

class UserChatProfile extends Component
{
    public $conversation;

    protected $listeners = [
        'conversationSelected' => 'getSelectedConversation',
    ];

    public function getSelectedConversation($conversation)
    {
        $this->conversation = $conversation;
    }
    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.user-chat-profile');
    }
}
