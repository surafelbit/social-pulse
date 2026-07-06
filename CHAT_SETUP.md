# Chat System Setup Guide

## Overview
A complete real-time chat system with WebSocket support using Laravel Reverb has been added to your social media application.

## Features
- ✅ Real-time messaging with WebSocket support
- ✅ Conversation management
- ✅ Message read status tracking
- ✅ User search for new conversations
- ✅ Responsive chat UI with Vue.js 3
- ✅ Automatic message broadcasting

## Installation Steps

### 1. Run Database Migrations
Run the migrations to create the necessary tables:

```bash
php artisan migrate
```

This will create:
- `conversations` table - Stores conversation data between users
- `messages` table - Stores individual messages

### 2. Start Reverb WebSocket Server
In a new terminal, start the Reverb server for real-time WebSocket connections:

```bash
php artisan reverb:start
```

Keep this running while developing. For production, use a process manager like Supervisor.

### 3. Build Frontend Assets
Compile Vue components and CSS:

```bash
npm run build
# or for development with hot reload:
npm run dev
```

### 4. Start Laravel Development Server
In another terminal, start the Laravel development server:

```bash
php artisan serve
```

### 5. Access the Chat Feature
- Navigate to `http://localhost:8000/messages`
- Click "New Message" to start a conversation
- Select a user to chat with
- Start chatting in real-time!

## Project Structure

### Database Models
- **Conversation** (`app/Models/Conversation.php`) - Represents a conversation between two users
- **Message** (`app/Models/Message.php`) - Represents individual messages

### Controllers
- **ConversationController** (`app/Http/Controllers/ConversationController.php`)
  - `index()` - List all conversations
  - `show()` - Display a specific conversation
  - `store()` - Create or retrieve a conversation

- **MessageController** (`app/Http/Controllers/MessageController.php`)
  - `store()` - Send a new message
  - `markAsRead()` - Mark message as read
  - `getMessages()` - Fetch paginated messages

### Events
- **MessageSent** (`app/Events/MessageSent.php`) - Broadcasts when a message is sent

### Routes
All chat routes are in `routes/web.php`:
- `GET /messages` - Chat conversations list
- `GET /messages/{conversation}` - View conversation
- `POST /messages` - Start/get conversation
- `POST /conversations/{conversation}/messages` - Send message
- `GET /conversations/{conversation}/messages` - Get paginated messages
- `PATCH /messages/{message}/read` - Mark message as read

### Vue Components
- **Chat/Index.vue** - Conversations list with user search
- **Chat/Show.vue** - Chat window with real-time messaging

## Environment Variables

Your `.env` file already has Reverb configured:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=470453
REVERB_APP_KEY=6ye0behga3ng4ihkwylv
REVERB_APP_SECRET=stmrisxl7u8pj8tae5hm
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

## How Real-Time Works

1. **Frontend**: Vue component listens on private WebSocket channel `chat.{conversation_id}`
2. **Backend**: When a message is sent, `MessageSent` event is dispatched
3. **Broadcasting**: Event is broadcast to all subscribed clients in the conversation
4. **Frontend**: Receives the broadcast and updates the UI in real-time

## Troubleshooting

### Messages not sending?
- Check if Reverb server is running on port 8080
- Check browser console for connection errors
- Verify VITE environment variables are set correctly

### WebSocket connection failed?
- Ensure Reverb is running: `php artisan reverb:start`
- Check if port 8080 is available and not blocked by firewall
- On Windows, you might need to run PowerShell as Administrator

### Messages not appearing in real-time?
- Refresh the page and try again
- Check if the browser has JavaScript enabled
- Check the Network tab in browser DevTools for WebSocket connection

## Production Deployment

For production:

1. Use a WebSocket reverse proxy (Nginx)
2. Use a process manager (Supervisor) for Reverb:
   ```bash
   [program:laravel-reverb]
   process_name=%(program_name)s_%(process_num)02d
   command=php /path/to/app/artisan reverb:start
   autostart=true
   autorestart=true
   numprocs=1
   redirect_stderr=true
   stdout_logfile=/var/log/reverb.log
   ```

3. Update `.env` with production Reverb host and port
4. Ensure SSL/TLS is configured (use `wss://` instead of `ws://`)

## Architecture Notes

The chat system uses:
- **Laravel Models** for database interaction
- **Laravel Broadcasting** for WebSocket events
- **Laravel Reverb** for WebSocket server
- **Vue 3** for reactive UI
- **Laravel Echo** for client-side WebSocket handling
- **Inertia.js** for server-side rendering

The system is fully secured with:
- Authentication middleware on all routes
- Private channel authorization in `routes/channels.php`
- Only users in a conversation can access messages
