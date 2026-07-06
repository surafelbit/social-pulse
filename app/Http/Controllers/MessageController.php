<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Store a new message.
     */
    public function store(Request $request, Conversation $conversation)
    {
        // Check if user is part of this conversation
        if ($conversation->user_id !== $request->user()->id && $conversation->recipient_id !== $request->user()->id) {
            return abort(403);
        }

        $request->validate([
            'body' => 'required|string|max:500',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $request->user()->id,
            'body' => $request->input('body'),
        ]);

        // Update conversation's last message timestamp
        $conversation->update(['last_message_at' => now()]);

        // Load sender relationship for broadcasting
        $message->load('sender');

        // Broadcast the message
        MessageSent::dispatch($message);

        return response()->json($message->load('sender'), 201);
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(Request $request, Message $message)
    {
        // Verify the user is the recipient
        $conversation = $message->conversation;
        if ($conversation->user_id !== $request->user()->id && $conversation->recipient_id !== $request->user()->id) {
            return abort(403);
        }

        if ($message->sender_id !== $request->user()->id && !$message->read_at) {
            $message->update(['read_at' => now()]);
        }

        return response()->json($message);
    }

    /**
     * Get paginated messages for a conversation.
     */
    public function getMessages(Request $request, Conversation $conversation)
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

        return response()->json($messages);
    }
}
