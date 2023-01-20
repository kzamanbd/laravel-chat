<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class UserTyping extends Component
{
    public $isTyping = false, $user = null;
    protected $listeners = [
        'showUserTyping' => 'showUserTyping',
        'hideUserTyping' => 'hideUserTyping',
    ];

    public function showUserTyping($value)
    {
        $this->isTyping = true;
        $this->user = $value['user'];
        $this->dispatchBrowserEvent('show-chat-detail');
    }
    public function hideUserTyping()
    {
        $this->isTyping = false;
    }

    public function render()
    {
        return view('livewire.user-typing');
    }
}
