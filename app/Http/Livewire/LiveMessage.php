<?php

namespace App\Http\Livewire;

use App\Events\SendNewMessage;
use Livewire\Component;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LiveMessage extends Component
{

    public $conversation, $newConversation, $newMessage, $conversation_id, $isSelectedConversation = false;

    protected $listeners = ['refreshMessage' => 'getMessage'];


    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required'
        ]);

        if (!isset($this->conversation->id)) {
            $conversation = Conversation::create([
                'from_user_id' => Auth::id(),
                'to_user_id' => $this->newConversation->id
            ]);
            $conversation_id = $conversation->id;
        } else {
            $conversation_id = $this->conversation->id;
        }

        $message = Message::create([
            'conversation_id' => $conversation_id,
            'user_id' => Auth::id(),
            'message' => $this->newMessage
        ]);

        $this->newMessage = null;

        broadcast(new SendNewMessage($message))->toOthers();
        $this->getMessage($conversation_id);
    }


    public function getMessage($id)
    {
        $this->isSelectedConversation = true;
        $this->conversation = Conversation::with(["from", "to", "message"])->find($id);
        $this->dispatchBrowserEvent('scroll-bottom');
        $this->emit('conversation', $this->conversation);
    }


    public function getConversationsProperty()
    {
        return Conversation::query()
            ->where("from_user_id", Auth::id())
            ->orWhere("to_user_id", Auth::id())
            ->with(["from", "to"])
            ->withCount(["unread_message"])
            ->get();
    }


    public function updateMessage($id)
    {
        Message::query()->where("conversation_id", $id)->update(["status" => 1]);
    }

    public function startNewConversation($id)
    {
        $this->conversation = null;
        $this->isSelectedConversation = true;
        $this->newConversation = User::find($id);
    }

    public function hasNewMessage()
    {
        $conversation = Conversation::query()->whereHas('message', function ($query) {
            return $query->where("status", "0");
        })->count();

        return (bool)$conversation;
    }

    public function getUsersProperty()
    {
        $ids = array_merge(
            [Auth::id()],
            $this->conversations->pluck('from_user_id')->toArray(),
            $this->conversations->pluck('to_user_id')->toArray()
        );
        return User::query()->whereNotIn("id", $ids)->get();
    }

    public function render()
    {
        return view('livewire.live-message');
    }
}
