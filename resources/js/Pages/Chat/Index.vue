<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Conversations List -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow">
                            <div class="p-4 border-b border-gray-200">
                                <button
                                    @click="
                                        showNewConversation =
                                            !showNewConversation
                                    "
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                >
                                    {{
                                        showNewConversation
                                            ? "Hide"
                                            : "New Message"
                                    }}
                                </button>
                            </div>

                            <!-- New Conversation Form -->
                            <div
                                v-if="showNewConversation"
                                class="p-4 border-b border-gray-200 bg-gray-50"
                            >
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search users..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3"
                                />
                                <div class="space-y-2 max-h-48 overflow-y-auto">
                                    <div
                                        v-for="user in filteredUsers"
                                        :key="user.id"
                                        @click="startConversation(user.id)"
                                        class="p-3 hover:bg-gray-100 cursor-pointer rounded transition flex items-center gap-3"
                                    >
                                        <img
                                            :src="
                                                user.avatar ||
                                                'https://via.placeholder.com/40'
                                            "
                                            :alt="user.name"
                                            class="w-10 h-10 rounded-full"
                                        />
                                        <div>
                                            <p class="font-semibold text-sm">
                                                {{ user.name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                @{{ user.username }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Conversations List -->
                            <div
                                class="divide-y divide-gray-200 max-h-96 overflow-y-auto"
                            >
                                <Link
                                    v-for="conversation in conversations.data"
                                    :key="conversation.id"
                                    :href="
                                        route(
                                            'conversations.show',
                                            conversation.id,
                                        )
                                    "
                                    class="p-4 hover:bg-gray-50 transition flex items-center gap-3"
                                >
                                    <img
                                        :src="
                                            getOtherUser(conversation)
                                                ?.avatar ||
                                            'https://via.placeholder.com/40'
                                        "
                                        :alt="getOtherUser(conversation)?.name"
                                        class="w-12 h-12 rounded-full"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="font-semibold text-sm truncate"
                                        >
                                            {{
                                                getOtherUser(conversation)?.name
                                            }}
                                        </p>
                                        <p
                                            class="text-xs text-gray-500 truncate"
                                        >
                                            {{
                                                conversation.latest_message
                                                    ?.body || "No messages yet"
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{
                                                formatTime(
                                                    conversation.last_message_at ||
                                                        conversation.created_at,
                                                )
                                            }}
                                        </p>
                                    </div>
                                </Link>
                            </div>

                            <!-- Pagination -->
                            <div
                                v-if="conversations.links"
                                class="p-4 border-t border-gray-200"
                            >
                                <div class="flex gap-2 text-sm">
                                    <Link
                                        v-for="link in conversations.links"
                                        :key="link.url"
                                        :href="link.url"
                                        :class="[
                                            'px-3 py-1 rounded',
                                            link.active
                                                ? 'bg-blue-600 text-white'
                                                : link.url
                                                  ? 'bg-gray-200 hover:bg-gray-300'
                                                  : 'bg-gray-100 text-gray-400 cursor-not-allowed',
                                        ]"
                                    >
                                        {{ link.label }}
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Welcome or Chat Window -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow h-full">
                            <div
                                v-if="!selectedConversation"
                                class="flex flex-col items-center justify-center h-96"
                            >
                                <svg
                                    class="w-16 h-16 text-gray-300 mb-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <p class="text-gray-500 text-lg">
                                    Select a conversation to start chatting
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    conversations: Object,
    users: Array,
});

const showNewConversation = ref(false);
const searchQuery = ref("");
const selectedConversation = ref(null);

const filteredUsers = computed(() => {
    if (!searchQuery.value) return props.users;
    return props.users.filter(
        (user) =>
            user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            user.username
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()),
    );
});

const getOtherUser = (conversation) => {
    const currentUserId = props.conversations?.user_id;
    return conversation.user_id === currentUserId
        ? conversation.recipient
        : conversation.user;
};

const formatTime = (date) => {
    if (!date) return "";
    const now = new Date();
    const then = new Date(date);
    const diff = now - then;

    if (diff < 3600000) return Math.floor(diff / 60000) + "m ago";
    if (diff < 86400000) return Math.floor(diff / 3600000) + "h ago";
    if (diff < 604800000) return Math.floor(diff / 86400000) + "d ago";
    return then.toLocaleDateString();
};

const startConversation = (userId) => {
    router.post(route("conversations.store"), { recipient_id: userId });
};
</script>
