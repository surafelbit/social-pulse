<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConversationController extends Controller
{
    /**
     * Display all conversations for the authenticated user.
     */
    public function index(Request $request)
    {
        $conversations = Conversation::where('user_id', $request->user()->id)
            ->orWhere('recipient_id', $request->user()->id)
            ->with(['user', 'recipient', 'latestMessage.sender'])
            ->orderBy('last_message_at', 'desc')
            ->paginate(15);

        return Inertia::render('Chat/Index', [
            'conversations' => $conversations,
            'users' => User::where('id', '!=', $request->user()->id)->get(['id', 'name', 'username', 'avatar']),
        ]);
    }

    /**
     * Show messages for a specific conversation.
     */
    public function show(Request $request, Conversation $conversation)
    {
        // Check if user is part of this conversation
        if ($conversation->user_id !== $request->user()->id && $conversation->recipient_id !== $request->user()->id) {
            return abort(403);
        }

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        // Mark messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return Inertia::render('Chat/Show', [
            'conversation' => $conversation->load(['user', 'recipient']),
            'messages' => $messages,
            'currentUser' => $request->user(),
        ]);
    }

    /**
     * Start a new conversation or get existing one.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id|not_in:' . $request->user()->id,
        ]);

        $userId = $request->user()->id;
        $recipientId = $request->input('recipient_id');

        // Check if conversation already exists
        $conversation = Conversation::where(function ($query) use ($userId, $recipientId) {
            $query->where('user_id', $userId)
                ->where('recipient_id', $recipientId);
        })->orWhere(function ($query) use ($userId, $recipientId) {
            $query->where('user_id', $recipientId)
                ->where('recipient_id', $userId);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $userId,
                'recipient_id' => $recipientId,
            ]);
        }

        return redirect()->route('conversations.show', $conversation);
    }
}
