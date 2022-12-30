<?php

namespace App\Http\Livewire;


use Livewire\Component;
use Illuminate\View\View;
use App\Models\Conversation;

class UserHead extends Component
{
    public Conversation $conversation;

    protected $listeners = [
        'userConversationSelected' => 'userConversationSelected',
    ];

    public function userConversationSelected($id)
    {
        $this->conversation = Conversation::find($id);
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.user-head');
    }
}