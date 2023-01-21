<?php

namespace App\Http\Livewire;


use Livewire\Component;
use Illuminate\View\View;
use App\Models\Conversation;

class UserHead extends Component
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
        return view('livewire.user-head');
    }
}
