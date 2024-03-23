<?php

namespace App\Livewire;

use App\Http\Helpers\Helpers;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;


#[Layout('layouts.chat')]
class Messages extends Component
{
    public $messages;
    public $messageText;
    public Conversation $conversation;

    // listen a event "conversation-selected"
    #[On('conversation-selected')]
    public function conversationSelectedListener($id)
    {
        $this->conversation = Conversation::with(['from', 'to', 'messages'])->find($id);

        $this->messages = collect($this->conversation->messages)->groupBy(function ($message) {
            return $message->created_at->format('d-M-Y');
        });
        // $this->updateMessageStatus($id);
    }

    public function updateMessageSeenTime($conversationId): void
    {
        $messages = Message::query()
            ->where('conversation_id', $conversationId)
            ->where('user_id', '!=', auth()->id())
            ->whereNull('seen_at')->get();

        if ($messages->count() > 0) {
            $messages->each(function ($message) {
                $message->update(['seen_at' => now()]);
            });
            $message = $messages->last();
            // broadcast(new MessageSeenTime($message))->toOthers();
        }
    }

    public function updateMessageStatus($conversationId): void
    {
        Message::query()
            ->where('conversation_id', $conversationId)
            ->where('user_id', '!=', auth()->id())
            ->where('is_seen', false)
            ->update(['is_seen' => true]);
        $this->updateMessageSeenTime($conversationId);
    }

    public function getUsersProperty()
    {
        return User::query()->whereNot('id', auth()->id())->get();
    }

    public function sendMessage(): void
    {
        if (!$this->conversation) {
            return;
        }

        $this->validate([
            'messageText' => 'required'
        ]);

        $messageText = preg_replace(Helpers::LINK_REGEX, Helpers::LINK_REPLACE, $this->messageText);
        $messageText = preg_replace(Helpers::EMAIL_REGEX, Helpers::EMAIL_REPLACE, $messageText);
        $messageText = preg_replace(Helpers::PHONE_REGEX, Helpers::PHONE_REPLACE, $messageText);

        Message::create([
            'conversation_id' => $this->conversation['id'],
            'user_id' => auth()->id(),
            'message' => $messageText
        ]);

        Conversation::find($this->conversation['id'])->update(['updated_at' => now()]);
        // broadcast(new MessageCreated($message, $this->targetUserId))->toOthers();
        $this->reset('messageText');
    }

    public function render()
    {
        return view('livewire.messages');
    }
}
