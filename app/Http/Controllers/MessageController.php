<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Helpers;
use App\Http\Requests\SendMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }

    public function index()
    {
        $conversations = Conversation::query()
            ->with(['participant'])
            ->whereAny(['from_user_id', 'to_user_id'], auth()->id())
            ->orderBy('updated_at', 'desc')
            ->get();

        $ids = collect(array_merge(
            $conversations->pluck('from_user_id')->toArray(),
            $conversations->pluck('to_user_id')->toArray()
        ))->unique();

        $users = User::query()->whereNotIn('id', $ids)->get();

        $conversation = null;
        $uuid = request()->input('uuid');
        if ($uuid) {
            $conversation = Conversation::query()
                ->with(['participant', 'messages:id,conversation_id,user_id,type,message,created_at', 'messages.user:id,name'])
                ->where('uuid', $uuid)
                ->first(['id', 'uuid', 'from_user_id', 'to_user_id', 'created_at', 'updated_at']);
        }

        return Inertia::render('Messages', [
            'users' => $users,
            'conversations' => $conversations,
            'conversation' => $conversation
        ]);
    }

    public function store(SendMessageRequest $request)
    {
        DB::transaction(function () use ($request) {
            $text = $request->input('message');
            $id = $request->input('conversation_id');
            if (empty($id)) {
                // check already has create a conversation
                $row = Conversation::where([
                    'from_user_id' => auth()->id(),
                    'to_user_id' => $request->input('to_user_id')
                ])->first();

                if (!$row) {
                    $id =  Conversation::insertGetId([
                        'from_user_id' => auth()->id(),
                        'to_user_id' => $request->input('to_user_id'),
                        'uuid' => Str::uuid()
                    ]);
                } else {
                    $id = $row->id;
                }
            }

            $messageText = preg_replace(Helpers::LINK_REGEX, Helpers::LINK_REPLACE, $text);
            $messageText = preg_replace(Helpers::EMAIL_REGEX, Helpers::EMAIL_REPLACE, $messageText);
            $messageText = preg_replace(Helpers::PHONE_REGEX, Helpers::PHONE_REPLACE, $messageText);

            Message::create([
                'conversation_id' => $id,
                'user_id' => auth()->id(),
                'message' => $messageText
            ]);

            Conversation::find($id)->update([
                //'msg_preview' => Str::substr($text, 0, 200),
                'updated_at' => now()
            ]);
        }, 3);
        return back();
        // broadcast(new MessageCreated($message, $this->targetUserId))->toOthers();
    }
}
