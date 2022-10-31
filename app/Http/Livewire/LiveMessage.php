<?php

namespace App\Http\Livewire;

use App\Events\SendNewMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;
use App\Models\Message;
use App\Models\MessageHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LiveMessage extends Component
{

    public $recentMessage, $newMessage, $messageText, $isSelectedMessage = false;

    protected $listeners = ['refreshMessage' => 'getMessage'];

    /**
     * @return void
     */
    public function sendMessage(): void
    {
        $this->validate([
            'messageText' => 'required'
        ]);

        if (!isset($this->recentMessage->id)) {
            $message = Message::create([
                'from_user_id' => Auth::id(),
                'to_user_id' => $this->newMessage->id
            ]);
            $message_id = $message->id;
        } else {
            $message_id = $this->recentMessage->id;
        }

        $messageHistory = MessageHistory::create([
            'message_id' => $message_id,
            'user_id' => Auth::id(),
            'message_text' => $this->messageText
        ]);

        $this->messageText = null;

        broadcast(new SendNewMessage($messageHistory))->toOthers();
        $this->getMessage($message_id);
    }

    /**
     * @param $id
     * @return void
     */
    public function getMessage($id): void
    {
        $this->isSelectedMessage = true;
        $this->recentMessage = Message::with(["from", "to", "message_history"])->find($id);
        $this->dispatchBrowserEvent('scroll-bottom');
        $this->emit('connect-message', $this->recentMessage);
    }

    /**
     * @return Collection
     */
    public function getMessagesProperty(): Collection
    {
        return Message::query()
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
    public function updateMessage($id): void
    {
        MessageHistory::query()->where("message_id", $id)->update(["status" => 1]);
    }

    /**
     * @param $id
     * @return void
     */
    public function startNewMessage($id): void
    {
        $this->recentMessage = null;
        $this->isSelectedMessage = true;
        $this->newMessage = User::find($id);
    }

    /**
     * @return bool
     */
    public function hasNewMessage(): bool
    {
        $message = Message::query()->whereHas('message_history', function ($query) {
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
            $this->messages->pluck('from_user_id')->toArray(),
            $this->messages->pluck('to_user_id')->toArray()
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
