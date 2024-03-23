<?php

namespace App\Livewire\Messages;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ConversationList extends Component
{
    public int $conversationId;

    public function getUserMessage($id)
    {
        $this->conversationId = $id;
        $this->dispatch('conversation-selected', $id);
    }
    /**
     * @return Collection
     */
    public function getConversationsProperty(): Collection
    {
        return Conversation::query()
            ->whereAny(['from_user_id', 'to_user_id'], auth()->id())
            ->with(['from', 'to'])
            ->withCount(['unreadMessage'])
            ->orderBy('updated_at', 'desc')
            ->get();
    }
    public function render()
    {
        return view('livewire.messages.conversation-list');
    }
}
