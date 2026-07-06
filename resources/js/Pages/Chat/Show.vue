<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-50">
      <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Chat Header -->
        <div class="bg-white rounded-lg shadow mb-6 p-4 flex items-center justify-between">
          <div class="flex items-center gap-4">
            <img
              :src="otherUser.avatar || 'https://via.placeholder.com/50'"
              :alt="otherUser.name"
              class="w-12 h-12 rounded-full"
            />
            <div>
              <h1 class="text-2xl font-bold text-gray-900">{{ otherUser.name }}</h1>
              <p class="text-sm text-gray-500">@{{ otherUser.username }}</p>
            </div>
          </div>
          <Link :href="route('conversations.index')" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </Link>
        </div>

        <!-- Messages Container -->
        <div class="bg-white rounded-lg shadow flex flex-col h-96 md:h-screen md:max-h-screen">
          <!-- Messages List -->
          <div ref="messagesContainer" class="flex-1 overflow-y-auto p-6 space-y-4">
            <div
              v-for="message in sortedMessages"
              :key="message.id"
              :class="[
                'flex',
                message.sender_id === currentUser.id ? 'justify-end' : 'justify-start'
              ]"
            >
              <div
                :class="[
                  'max-w-xs lg:max-w-md px-4 py-2 rounded-lg',
                  message.sender_id === currentUser.id
                    ? 'bg-blue-600 text-white'
                    : 'bg-gray-200 text-gray-900'
                ]"
              >
                <p class="text-sm break-words">{{ message.body }}</p>
                <p
                  :class="[
                    'text-xs mt-1',
                    message.sender_id === currentUser.id ? 'text-blue-100' : 'text-gray-500'
                  ]"
                >
                  {{ formatTime(message.created_at) }}
                </p>
              </div>
            </div>
            <div v-if="messages.length === 0" class="text-center text-gray-500 py-8">
              <p>No messages yet. Start the conversation!</p>
            </div>
          </div>

          <!-- Message Input -->
          <div class="border-t border-gray-200 p-4">
            <form @submit.prevent="sendMessage" class="flex gap-2">
              <input
                v-model="newMessage"
                type="text"
                placeholder="Type a message..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="sending"
              />
              <button
                type="submit"
                :disabled="!newMessage.trim() || sending"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 transition"
              >
                <svg v-if="!sending" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <span v-else class="text-sm">Sending...</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import Echo from 'laravel-echo';

const props = defineProps({
  conversation: Object,
  messages: Object,
  currentUser: Object,
});

const newMessage = ref('');
const sending = ref(false);
const messagesContainer = ref(null);
const messages = ref(props.messages?.data || []);
const echo = ref(null);

const otherUser = computed(() => {
  return props.conversation.user_id === props.currentUser.id
    ? props.conversation.recipient
    : props.conversation.user;
});

const sortedMessages = computed(() => {
  return [...messages.value].sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
});

const formatTime = (date) => {
  return new Date(date).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
};

const scrollToBottom = async () => {
  await nextTick();
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
  }
};

const sendMessage = async () => {
  if (!newMessage.value.trim()) return;

  sending.value = true;
  try {
    const response = await axios.post(
      route('messages.store', props.conversation.id),
      { body: newMessage.value }
    );

    newMessage.value = '';
    await scrollToBottom();
  } catch (error) {
    console.error('Error sending message:', error);
  } finally {
    sending.value = false;
  }
};

const setupWebSocket = () => {
  if (typeof window.Echo === 'undefined') {
    console.warn('Laravel Echo not initialized');
    return;
  }

  echo.value = window.Echo;

  echo.value
    .private(`chat.${props.conversation.id}`)
    .listen('message.sent', (data) => {
      messages.value.push({
        id: data.id,
        conversation_id: data.conversation_id,
        sender_id: data.sender_id,
        sender: data.sender,
        body: data.body,
        created_at: data.created_at,
        read_at: data.read_at,
      });
      scrollToBottom();

      // Mark message as read if it's not from current user
      if (data.sender_id !== props.currentUser.id) {
        axios.patch(route('messages.read', data.id)).catch(() => {});
      }
    });
};

onMounted(() => {
  setupWebSocket();
  scrollToBottom();
});

watch(
  () => props.messages,
  (newMessages) => {
    if (newMessages?.data) {
      messages.value = newMessages.data;
      scrollToBottom();
    }
  }
);
</script>
