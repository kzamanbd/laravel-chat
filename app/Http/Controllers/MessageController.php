<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
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
            ->where('from_user_id', auth()->id())
            ->orWhere('to_user_id', auth()->id())
            ->with(['from', 'to', 'messages'])
            ->withCount(['unreadMessage'])
            ->orderBy('updated_at', 'desc')
            ->get();
        $users = User::query()->whereNot('id', auth()->id())->get();

        return Inertia::render('Messages', [
            'conversations' => $conversations,
            'users' => $users,
        ]);
    }
}
