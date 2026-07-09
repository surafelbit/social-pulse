<template>
    <AuthenticatedLayout>
        <div
            class="font-body-md min-h-screen flex flex-col transition-colors duration-300"
            style="background-color: var(--sp-bg); color: var(--sp-text)"
        >
            <!-- Top Navigation (matching login style) -->
            <nav
                class="w-full top-0 sticky z-50 sp-chat-nav"
                style="
                    border-bottom: 1px solid var(--sp-border);
                    background-color: color-mix(
                        in srgb,
                        var(--sp-bg) 85%,
                        transparent
                    );
                    backdrop-filter: blur(16px);
                "
            >
                <div
                    class="flex justify-between items-center h-16 px-margin-desktop max-w-container-max mx-auto"
                >
                    <div class="flex items-center gap-3">
                        <Link href="/" class="flex items-center gap-2.5 group">
                            <span
                                class="w-2.5 h-2.5 rounded-full bg-[#32cd32] shadow-[0_0_16px_rgba(50,205,50,0.7)]"
                            ></span>
                            <span
                                class="font-headline-md text-headline-md font-bold tracking-tight"
                                style="color: var(--sp-text)"
                            >
                                Social<span class="sp-login-gradient-text"
                                    >Pulse</span
                                >
                            </span>
                        </Link>

                        <div
                            class="hidden sm:flex items-center text-sm text-[var(--sp-text-2)]"
                        >
                            <span class="mx-3">•</span>
                            Messages
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Theme Toggle -->
                        <button
                            @click="toggleTheme"
                            class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[#32cd32]/10 hover:text-[#32cd32]"
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
                            <svg
                                v-if="isDark"
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                                />
                            </svg>
                            <svg
                                v-else
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"
                                />
                            </svg>
                        </button>

                        <Link
                            :href="route('conversations.index')"
                            class="flex items-center gap-2 px-4 py-2 rounded-xl hover:bg-[#32cd32]/10 transition-colors text-sm font-medium"
                            style="color: var(--sp-text-2)"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                            All Chats
                        </Link>
                    </div>
                </div>
            </nav>

            <div
                class="flex-1 flex max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-6 gap-6"
            >
                <!-- Chat Container -->
                <div
                    class="flex-1 flex flex-col kinetic-shadow rounded-3xl overflow-hidden border border-[var(--sp-border)]"
                    style="
                        background-color: var(--sp-card);
                        height: calc(100vh - 120px);
                    "
                >
                    <!-- Chat Header -->
                    <div
                        class="px-6 py-5 border-b border-[var(--sp-border)] flex items-center justify-between bg-[color-mix(in_srgb,var(--sp-bg)_70%,transparent)]"
                    >
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <img
                                    :src="
                                        otherUser.avatar ||
                                        'https://via.placeholder.com/48'
                                    "
                                    :alt="otherUser.name"
                                    class="w-11 h-11 rounded-2xl object-cover ring-2 ring-[#32cd32]/20"
                                />
                                <div
                                    class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-[#32cd32] rounded-full ring-2 ring-[var(--sp-card)]"
                                ></div>
                            </div>
                            <div>
                                <h1
                                    class="font-headline-md text-headline-md font-semibold"
                                    style="color: var(--sp-text)"
                                >
                                    {{ otherUser.name }}
                                </h1>
                                <p
                                    class="text-sm"
                                    style="color: var(--sp-text-2)"
                                >
                                    @{{ otherUser.username }} • Online
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                class="p-3 rounded-2xl hover:bg-[#32cd32]/10 transition-colors"
                                style="color: var(--sp-text-2)"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17 20h5v-2a3 3 0 01-5.356-1.857M17 20H7m5-2v-2c0-.656-.126-1.284-.356-1.852M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.284.356-1.852m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                            </button>
                            <button
                                class="p-3 rounded-2xl hover:bg-[#32cd32]/10 transition-colors"
                                style="color: var(--sp-text-2)"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 5a2 2 0 012-2 2 2 0 01-2-2 2 2 0 01-2-2 2 2 0 012-2 2 2 0 01-2-2 2 2 0 012-2zM15 5a2 2 0 012-2 2 2 0 01-2-2 2 2 0 01-2-2 2 2 0 012-2z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div
                        ref="messagesContainer"
                        class="flex-1 overflow-y-auto p-6 space-y-6 bg-[var(--sp-bg)] custom-scrollbar"
                    >
                        <div
                            v-for="message in sortedMessages"
                            :key="message.id"
                            :class="[
                                'flex',
                                message.sender_id === currentUser.id
                                    ? 'justify-end'
                                    : 'justify-start',
                            ]"
                        >
                            <div
                                :class="[
                                    'max-w-[70%] px-5 py-3 rounded-3xl text-body-md',
                                    message.sender_id === currentUser.id
                                        ? 'bg-gradient-to-br from-[#32cd32] to-[#28a828] text-[#06120a] rounded-br-none'
                                        : 'bg-[var(--sp-bg-2)] text-[var(--sp-text)] border border-[var(--sp-border)] rounded-bl-none',
                                ]"
                            >
                                <p class="break-words leading-relaxed">
                                    {{ message.body }}
                                </p>
                                <p
                                    :class="[
                                        'text-[10px] mt-2 opacity-75 text-right',
                                        message.sender_id === currentUser.id
                                            ? 'text-[#06120a]/70'
                                            : 'text-[var(--sp-text-3)]',
                                    ]"
                                >
                                    {{ formatTime(message.created_at) }}
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="messages.length === 0"
                            class="flex flex-col items-center justify-center h-64 text-center"
                        >
                            <div
                                class="w-20 h-20 rounded-3xl bg-[#32cd32]/10 flex items-center justify-center mb-6"
                            >
                                <span class="text-4xl">💬</span>
                            </div>
                            <p class="font-medium text-[var(--sp-text-2)]">
                                No messages yet
                            </p>
                            <p
                                class="text-sm mt-1"
                                style="color: var(--sp-text-3)"
                            >
                                Start the conversation with a wave 👋
                            </p>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div
                        class="p-5 border-t border-[var(--sp-border)] bg-[var(--sp-card)]"
                    >
                        <form @submit.prevent="sendMessage" class="flex gap-3">
                            <div class="flex-1 relative">
                                <input
                                    v-model="newMessage"
                                    type="text"
                                    placeholder="Type your message..."
                                    class="sp-chat-input w-full h-14 pl-6 pr-5 text-body-md rounded-3xl transition-all"
                                    style="
                                        background-color: var(--sp-bg-2);
                                        color: var(--sp-text);
                                        border: 1px solid var(--sp-border);
                                    "
                                    :disabled="sending"
                                />
                            </div>

                            <button
                                type="submit"
                                :disabled="!newMessage.trim() || sending"
                                class="h-14 w-14 flex items-center justify-center rounded-3xl sp-login-submit transition-all active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed"
                            >
                                <svg
                                    v-if="!sending"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2.25"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.874L5.999 12zm0 0h7.07"
                                    />
                                </svg>
                                <span
                                    v-else
                                    class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"
                                ></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer (minimal) -->
            <footer
                class="py-6 text-center text-xs"
                style="
                    color: var(--sp-text-3);
                    border-top: 1px solid var(--sp-border);
                "
            >
                © 2026 Social Pulse • Secure Chat
            </footer>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from "vue";
import { Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";
import Echo from "laravel-echo";

// Theme handling (same as login)
const isDark = ref(
    typeof window !== "undefined"
        ? document.documentElement.classList.contains("dark")
        : false,
);

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

// Props & Reactive
const props = defineProps({
    conversation: Object,
    messages: Object,
    currentUser: Object,
});

const newMessage = ref("");
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
    return [...messages.value].sort(
        (a, b) => new Date(a.created_at) - new Date(b.created_at),
    );
});

const formatTime = (date) => {
    return new Date(date).toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
    });
};

const scrollToBottom = async () => {
    await nextTick();
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop =
            messagesContainer.value.scrollHeight;
    }
};

const sendMessage = async () => {
    if (!newMessage.value.trim()) return;

    sending.value = true;
    try {
        const response = await axios.post(route("messages.store", props.conversation.id), {
            body: newMessage.value,
        });
        const createdMessage = response.data;
        if (!messages.value.some((m) => m.id === createdMessage.id)) {
            messages.value.push(createdMessage);
        }
        newMessage.value = "";
        await scrollToBottom();
    } catch (error) {
        console.error("Error sending message:", error);
    } finally {
        sending.value = false;
    }
};

const setupWebSocket = () => {
    if (typeof window.Echo === "undefined") {
        console.warn("Laravel Echo not initialized");
        return;
    }

    echo.value = window.Echo;

    echo.value
        .private(`chat.${props.conversation.id}`)
        .listen("message.sent", (data) => {
            if (!messages.value.some((m) => m.id === data.id)) {
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
            }

            if (data.sender_id !== props.currentUser.id) {
                axios.patch(route("messages.read", data.id)).catch(() => {});
            }
        });
};

onMounted(() => {
    setupWebSocket();
    scrollToBottom();

    // Initialize theme
    const saved = localStorage.getItem("sp-theme");
    if (saved === "dark") {
        document.documentElement.classList.add("dark");
        isDark.value = true;
    } else if (saved === "light") {
        document.documentElement.classList.remove("dark");
        isDark.value = false;
    } else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
        document.documentElement.classList.add("dark");
        isDark.value = true;
    }
});

watch(
    () => props.messages,
    (newMessages) => {
        if (newMessages?.data) {
            messages.value = newMessages.data;
            scrollToBottom();
        }
    },
);
</script>

<style scoped>
/* Chat-specific enhancements matching login aesthetic */
.sp-chat-input {
    caret-color: #32cd32;
}
.sp-chat-input::placeholder {
    color: var(--sp-text-3);
    opacity: 0.75;
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
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(50, 205, 50, 0.5);
}

/* Kinetic shadow & hover effects */
.kinetic-shadow {
    transition:
        box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1),
        border-color 0.4s ease;
}
.kinetic-shadow:hover {
    box-shadow: 0 20px 40px rgba(50, 205, 50, 0.18);
    border-color: rgba(50, 205, 50, 0.25);
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
