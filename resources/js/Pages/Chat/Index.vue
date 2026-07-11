<template>
    <div
        class="font-body-md min-h-screen flex flex-col transition-colors duration-300"
        style="background-color: var(--sp-bg); color: var(--sp-text)"
    >
        <header class="sticky top-0 z-50 sp-header">
            <div
                class="max-w-6xl mx-auto px-4 md:px-6 h-16 flex items-center justify-between gap-4"
            >
                <!-- Logo -->
                <div class="flex items-center gap-2.5">
                    <span
                        class="w-2.5 h-2.5 rounded-full bg-[#32cd32] shadow-[0_0_12px_rgba(50,205,50,0.7)]"
                    ></span>
                    <Link
                        :href="route('dashboard')"
                        class="font-headline font-bold text-lg tracking-tight"
                        style="color: var(--sp-text)"
                    >
                        Social<span class="sp-gradient-text">Pulse</span>
                    </Link>
                </div>

                <!-- User Search Bar -->
                <UserSearch />

                <!-- Right side nav -->
                <nav class="flex items-center gap-3 md:gap-5">
                    <!-- Profile link -->
                    <Link
                        :href="
                            route(
                                'profile.show',
                                $page.props.auth.user.username,
                            )
                        "
                        class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide transition-colors duration-200 hover:text-[#32cd32]"
                        style="color: var(--sp-text-2)"
                    >
                        <div class="w-7 h-7 sp-avatar text-[10px]">
                            {{ $page.props.auth.user.name?.charAt(0) ?? "?" }}
                        </div>
                        <span class="hidden sm:inline">{{
                            $page.props.auth.user.name
                        }}</span>
                    </Link>

                    <!-- Theme toggle -->
                    <button
                        @click="toggleTheme"
                        class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[#32cd32]/10 hover:text-[#32cd32] focus:outline-none"
                        style="
                            color: var(--sp-text-2);
                            border: 1px solid var(--sp-border);
                        "
                        :title="
                            isDark
                                ? 'Switch to light mode'
                                : 'Switch to dark mode'
                        "
                    >
                        <!-- Sun icon (light mode) -->
                        <svg
                            v-if="isDark"
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                            />
                        </svg>
                        <!-- Moon icon (dark mode) -->
                        <svg
                            v-else
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"
                            />
                        </svg>
                    </button>

                    <!-- Feed Link -->
                    <Link
                        :href="route('dashboard')"
                        class="text-xs font-semibold uppercase tracking-wide transition-colors hover:text-[#32cd32]"
                        style="color: var(--sp-text-2)"
                    >
                        Feed
                    </Link>

                    <!-- Messages -->
                    <Link
                        :href="route('conversations.index')"
                        class="relative flex items-center justify-center w-9 h-9 rounded-full transition-colors bg-[#32cd32]/10 text-[#32cd32]"
                        aria-label="Messages"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </Link>

                    <!-- Notifications -->
                    <Link :href="route('notifications.index')" class="relative flex items-center justify-center w-9 h-9 rounded-full transition-colors hover:bg-[#32cd32]/10 text-[var(--sp-text-2)]" aria-label="Notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            v-if="$page.props.auth.unreadNotifications > 0"
                            class="absolute -top-0.5 -right-0.5 min-w-[1.125rem] h-[1.125rem] px-1 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none"
                        >
                            {{ $page.props.auth.unreadNotifications > 9 ? '9+' : $page.props.auth.unreadNotifications }}
                        </span>
                    </Link>

                    <!-- Logout -->
                    <button
                        @click="logout"
                        class="hidden sm:flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide transition-colors duration-200 hover:text-red-400"
                        style="color: var(--sp-text-2)"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-3.5 h-3.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"
                            />
                        </svg>
                        Logout
                    </button>
                </nav>
            </div>
        </header>

        <div
            class="flex-1 max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-8"
        >
                <div
                    class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-[calc(100vh-140px)]"
                >
                    <!-- Conversations Sidebar -->
                    <div class="lg:col-span-5 xl:col-span-4 flex flex-col">
                        <div
                            class="kinetic-shadow rounded-3xl overflow-hidden border border-[var(--sp-border)] flex-1 flex flex-col"
                            style="background-color: var(--sp-card)"
                        >
                            <!-- Header -->
                            <div class="p-6 border-b border-[var(--sp-border)]">
                                <h1
                                    class="font-headline-lg text-headline-lg"
                                    style="color: var(--sp-text)"
                                >
                                    Messages
                                </h1>
                                <button
                                    @click="
                                        showNewConversation =
                                            !showNewConversation
                                    "
                                    class="mt-4 w-full h-12 rounded-2xl sp-login-submit font-medium flex items-center justify-center gap-2 transition-all active:scale-[0.98]"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="3"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 4v16m8-8H4"
                                        />
                                    </svg>
                                    New Message
                                </button>
                            </div>

                            <!-- New Conversation Panel -->
                            <div
                                v-if="showNewConversation"
                                class="p-6 border-b border-[var(--sp-border)] bg-[var(--sp-bg-2)]"
                            >
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search users to message..."
                                    class="sp-chat-input w-full h-12 px-5 rounded-2xl text-body-md"
                                    style="
                                        background-color: var(--sp-bg);
                                        border: 1px solid var(--sp-border);
                                    "
                                />

                                <div
                                    class="mt-4 max-h-64 overflow-y-auto custom-scrollbar space-y-1"
                                >
                                    <div
                                        v-for="user in filteredUsers"
                                        :key="user.id"
                                        @click="startConversation(user.id)"
                                        class="flex items-center gap-4 p-3 hover:bg-[#32cd32]/10 rounded-2xl cursor-pointer transition-all group"
                                    >
                                        <img
                                            :src="
                                                user.avatar ||
                                                'https://via.placeholder.com/48'
                                            "
                                            :alt="user.name"
                                            class="w-11 h-11 rounded-2xl object-cover ring-2 ring-offset-2 ring-offset-[var(--sp-card)] ring-[#32cd32]/20"
                                        />
                                        <div class="flex-1">
                                            <p
                                                class="font-semibold"
                                                style="color: var(--sp-text)"
                                            >
                                                {{ user.name }}
                                            </p>
                                            <p
                                                class="text-sm"
                                                style="color: var(--sp-text-2)"
                                            >
                                                @{{ user.username }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Conversations List -->
                            <div
                                class="flex-1 overflow-y-auto custom-scrollbar divide-y divide-[var(--sp-border)]"
                            >
                                <template v-if="conversations && conversations.data && conversations.data.length > 0">
                                    <Link
                                        v-for="conversation in conversations.data"
                                        :key="conversation.id"
                                        :href="
                                            route(
                                                'conversations.show',
                                                conversation.id,
                                            )
                                        "
                                        class="p-5 flex items-center gap-4 hover:bg-[var(--sp-bg-2)] transition-all group"
                                    >
                                        <div class="relative">
                                            <img
                                                :src="
                                                    getOtherUser(conversation)
                                                        ?.avatar ||
                                                    'https://via.placeholder.com/48'
                                                "
                                                :alt="
                                                    getOtherUser(conversation)?.name
                                                "
                                                class="w-14 h-14 rounded-2xl object-cover"
                                            />
                                            <div
                                                class="absolute bottom-0 right-0 w-4 h-4 bg-[#32cd32] rounded-full ring-2 ring-[var(--sp-card)]"
                                            ></div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div
                                                class="flex justify-between items-baseline"
                                            >
                                                <p
                                                    class="font-semibold truncate"
                                                    style="color: var(--sp-text)"
                                                >
                                                    {{
                                                        getOtherUser(conversation)
                                                            ?.name
                                                    }}
                                                </p>
                                                <p
                                                    class="text-xs whitespace-nowrap"
                                                    style="color: var(--sp-text-3)"
                                                >
                                                    {{
                                                        formatTime(
                                                            conversation.last_message_at ||
                                                                conversation.created_at,
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                            <p
                                                class="text-sm truncate mt-1"
                                                style="color: var(--sp-text-2)"
                                            >
                                                {{
                                                    conversation.latest_message
                                                        ?.body || "Start chatting"
                                                }}
                                            </p>
                                        </div>
                                    </Link>
                                </template>
                                <template v-else>
                                    <div class="p-6 text-center text-sm text-[var(--sp-text-3)] border-b border-[var(--sp-border)]">
                                        No recent chats. Start a conversation below!
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-[var(--sp-text-3)] mb-3 px-2">
                                            All Users
                                        </p>
                                        <div class="space-y-1">
                                            <div
                                                v-for="user in users"
                                                :key="user.id"
                                                @click="startConversation(user.id)"
                                                class="flex items-center gap-4 p-3 hover:bg-[#32cd32]/10 rounded-2xl cursor-pointer transition-all group"
                                            >
                                                <img
                                                    :src="
                                                        user.avatar ||
                                                        'https://via.placeholder.com/48'
                                                    "
                                                    :alt="user.name"
                                                    class="w-10 h-10 rounded-2xl object-cover ring-2 ring-offset-2 ring-offset-[var(--sp-card)] ring-[#32cd32]/20"
                                                />
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-semibold text-sm truncate" style="color: var(--sp-text)">
                                                        {{ user.name }}
                                                    </p>
                                                    <p class="text-xs truncate" style="color: var(--sp-text-2)">
                                                        @{{ user.username }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Pagination -->
                            <div
                                v-if="conversations && conversations.links"
                                class="p-5 border-t border-[var(--sp-border)]"
                            >
                                <div class="flex flex-wrap gap-2">
                                        <div v-for="link in conversations.links" :key="link.label">
                                            <Link
                                                v-if="link.url"
                                                :href="link.url"
                                                :class="[
                                                    'px-4 py-2 text-sm rounded-2xl transition-all',
                                                    link.active
                                                        ? 'bg-[#32cd32] text-black font-medium'
                                                        : 'hover:bg-[#32cd32]/10 text-[var(--sp-text-2)]',
                                                ]"
                                            >
                                                {{ link.label }}
                                            </Link>
                                            <span
                                                v-else
                                                class="px-4 py-2 text-sm rounded-2xl transition-all opacity-40 cursor-not-allowed"
                                            >
                                                {{ link.label }}
                                            </span>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Chat Area / Placeholder -->
                    <div class="lg:col-span-7 xl:col-span-8">
                        <div
                            class="kinetic-shadow rounded-3xl h-full border border-[var(--sp-border)] flex items-center justify-center"
                            style="background-color: var(--sp-card)"
                        >
                            <div class="text-center max-w-md px-8">
                                <div
                                    class="mx-auto w-24 h-24 rounded-3xl bg-[#32cd32]/10 flex items-center justify-center mb-8"
                                >
                                    <span class="text-6xl">💬</span>
                                </div>
                                <h2
                                    class="font-headline-md text-3xl mb-3"
                                    style="color: var(--sp-text)"
                                >
                                    Your Messages
                                </h2>
                                <p
                                    class="text-[var(--sp-text-2)] leading-relaxed"
                                >
                                    Select a conversation from the sidebar or
                                    start a new one to connect with friends and
                                    the community.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import UserSearch from "@/Components/UserSearch.vue";

const logout = () => router.post(route("logout"));

const props = defineProps({
    conversations: Object,
    users: Array,
});

const showNewConversation = ref(false);
const searchQuery = ref("");

// Theme handling (consistent with other pages)
const isDark = ref(false);

const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add("dark");
        localStorage.setItem("sp-theme", "dark");
    } else {
        document.documentElement.classList.remove("dark");
        localStorage.setItem("sp-theme", "light");
    }
};

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

const page = usePage();

const getOtherUser = (conversation) => {
    const currentUserId = page.props.auth.user?.id;
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

// Initialize theme on mount
onMounted(() => {
    const saved = localStorage.getItem("sp-theme");
    if (
        saved === "dark" ||
        (!saved && window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
        document.documentElement.classList.add("dark");
        isDark.value = true;
    }
});
</script>

<style scoped>
.sp-chat-input {
    caret-color: #32cd32;
}
.sp-chat-input::placeholder {
    color: var(--sp-text-3);
}
.sp-chat-input:focus {
    outline: none;
    border-color: #32cd32 !important;
    box-shadow: 0 0 0 4px rgba(50, 205, 50, 0.15);
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(50, 205, 50, 0.3);
    border-radius: 20px;
}

.kinetic-shadow {
    transition: box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.kinetic-shadow:hover {
    box-shadow: 0 25px 50px rgba(50, 205, 50, 0.18);
}

.sp-login-gradient-text {
    background: linear-gradient(135deg, #32cd32 0%, #4ce346 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.sp-login-submit {
    background: linear-gradient(135deg, #32cd32 0%, #28a828 100%);
    color: #06120a;
}
</style>
