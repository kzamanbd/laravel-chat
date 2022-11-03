<?php

namespace App\Http\Livewire;

use App\Events\MessageCreated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LiveMessage extends Component
{

    public $conversation, $newMessage, $messageText, $isSelected = false;

    protected $listeners = ['refreshMessage' => 'getMessage'];

    /**
     * @return void
     */
    public function sendMessage(): void
    {
        $this->validate([
            'messageText' => 'required'
        ]);

        if (!isset($this->conversation->id)) {
            $conversation = Conversation::create([
                'from_user_id' => Auth::id(),
                'to_user_id' => $this->newMessage->id
            ]);
            $conversation_id = $conversation->id;
        } else {
            $conversation_id = $this->conversation->id;
        }

        $message = Message::create([
            'conversation_id' => $conversation_id,
            'user_id' => Auth::id(),
            'message_text' => $this->messageText
        ]);
        broadcast(new MessageCreated($message))->toOthers();
        $this->messageText = null;
        $this->getMessage($conversation_id);
    }

    /**
     * @param $id
     * @return void
     */
    public function getMessage($id): void
    {
        $this->isSelected = true;
        $this->conversation = Conversation::with(["from", "to", "messages"])->find($id);
        $this->dispatchBrowserEvent('scroll-bottom');
        $this->emit('connect-message', $this->conversation);
    }

    /**
     * @return Collection
     */
    public function getConversationsProperty(): Collection
    {
        return Conversation::query()
            ->where("from_user_id", Auth::id())
            ->orWhere("to_user_id", Auth::id())
            ->with(["from", "to"])
            ->withCount(["unread_message"])
            ->get();
    }

    /**
     * @param $id
     * @return void
     */
    public function updateMessageStatus($id): void
    {
        Message::query()->where("conversation_id", $id)->update(["status" => 1]);
    }

    /**
     * @param $id
     * @return void
     */
    public function startNewMessage($id): void
    {
        $this->conversation = null;
        $this->isSelected = true;
        $this->newMessage = User::find($id);
    }

    /**
     * @return bool
     */
    public function hasNewMessage(): bool
    {
        $message = Conversation::query()->whereHas('messages', function ($query) {
            return $query->where("status", "0");
        })->count();

        return (bool)$message;
    }

    /**
     * @return Collection
     */
    public function getUsersProperty(): Collection
    {
        $ids = array_merge(
            [Auth::id()],
            $this->conversations->pluck('from_user_id')->toArray(),
            $this->conversations->pluck('to_user_id')->toArray()
        );
        return User::query()->whereNotIn("id", $ids)->get();
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.live-message');
    }
}
