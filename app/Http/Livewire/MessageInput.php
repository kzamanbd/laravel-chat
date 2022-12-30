<?php

namespace App\Http\Livewire;


use Livewire\Component;
use Illuminate\View\View;

class MessageInput extends Component
{
    public $conversationId;

    protected $listeners = [
        'userConversationSelected' => 'userConversationSelected',
    ];

    public function userConversationSelected($conversationId)
    {
        $this->conversationId = $conversationId;
    }
    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.message-input');
    }
}
