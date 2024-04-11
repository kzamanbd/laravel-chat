<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Helpers;
use App\Http\Requests\SendMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }
    public function index()
    {
        $conversations = Conversation::query()
            ->whereAny(['from_user_id', 'to_user_id'], auth()->id())
            ->with(['fromUser:id,name,avatar_path', 'toUser:id,name,avatar_path', 'messages'])
            ->withCount(['unreadMessage'])
            ->orderBy('updated_at', 'desc')
            ->get();
        $users = User::query()->whereNot('id', auth()->id())->get();

        return Inertia::render('Messages', [
            'conversations' => $conversations,
            'users' => $users,
        ]);
    }

    public function store(SendMessageRequest $request)
    {
        $message = $request->input('message');
        $conversationId = $request->input('conversation_id');

        $messageText = preg_replace(Helpers::LINK_REGEX, Helpers::LINK_REPLACE, $message);
        $messageText = preg_replace(Helpers::EMAIL_REGEX, Helpers::EMAIL_REPLACE, $messageText);
        $messageText = preg_replace(Helpers::PHONE_REGEX, Helpers::PHONE_REPLACE, $messageText);

        $message = Message::create([
            'conversation_id' => $conversationId,
            'user_id' => auth()->id(),
            'message' => $messageText
        ]);

        Conversation::find($conversationId)->update(['updated_at' => now()]);

        // broadcast(new MessageCreated($message, $this->targetUserId))->toOthers();

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }
}
