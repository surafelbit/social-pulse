<script setup>
import { Link } from '@inertiajs/vue3';
import ToastContainer from '@/Components/ToastContainer.vue';

const props = defineProps({
    notifications: {
        type: Array,
        default: () => [],
    },
});

const iconFor = (type) => {
    if (type === 'like') return 'favorite';
    if (type === 'follow') return 'person_add';
    if (type === 'comment') return 'chat_bubble';
    return 'notifications';
};

const messageFor = (note) => {
    const name = note.sender?.name ?? note.sender?.username ?? 'Someone';
    if (note.type === 'like') return `${name} liked your post`;
    if (note.type === 'follow') return `${name} started following you`;
    if (note.type === 'comment') return `${name} commented on your post`;
    return `${name} sent you a notification`;
};

const linkFor = (note) => {
    if (note.type === 'follow' && note.sender?.username) {
        return route('profile.show', note.sender.username);
    }
    return route('dashboard');
};

const timeAgo = (iso) => {
    const diff = Date.now() - new Date(iso).getTime();
    const mins = Math.floor(diff / 60000);
    if (mins < 1) return 'Just now';
    if (mins < 60) return `${mins}m ago`;
    const hours = Math.floor(mins / 60);
    if (hours < 24) return `${hours}h ago`;
    const days = Math.floor(hours / 24);
    if (days < 7) return `${days}d ago`;
    return new Date(iso).toLocaleDateString();
};
</script>

<template>
    <div
        class="min-h-screen transition-colors duration-300"
        style="background-color: var(--sp-bg); color: var(--sp-text);"
    >
        <header class="sticky top-0 z-50 sp-header">
            <div class="max-w-2xl mx-auto px-4 md:px-6 h-16 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('dashboard')"
                        class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[#32cd32]/10 hover:text-[#32cd32]"
                        style="color: var(--sp-text-2); border: 1px solid var(--sp-border);"
                        aria-label="Back to feed"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                        </svg>
                    </Link>
                    <div>
                        <h1 class="font-headline font-bold text-lg tracking-tight">Notifications</h1>
                        <p class="text-xs" style="color: var(--sp-text-3);">Your latest activity</p>
                    </div>
                </div>
                <Link
                    :href="route('dashboard')"
                    class="text-xs font-semibold uppercase tracking-wide transition-colors hover:text-[#32cd32]"
                    style="color: var(--sp-text-2);"
                >
                    Feed
                </Link>
            </div>
        </header>

        <main class="max-w-2xl mx-auto px-4 md:px-6 py-8">
            <div v-if="notifications.length > 0" class="space-y-3">
                <Link
                    v-for="note in notifications"
                    :key="note.id"
                    :href="linkFor(note)"
                    class="sp-notification-card sp-glass-card block p-4 rounded-2xl transition-all duration-200"
                    :class="{ 'sp-notification-card--unread': !note.is_read }"
                >
                    <div class="flex items-start gap-3">
                        <div class="sp-notification-icon">
                            <span class="material-symbols-outlined text-xl">{{ iconFor(note.type) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold leading-snug">{{ messageFor(note) }}</p>
                            <p class="text-xs mt-1" style="color: var(--sp-text-3);">
                                {{ timeAgo(note.created_at) }}
                            </p>
                        </div>
                        <span
                            v-if="!note.is_read"
                            class="w-2 h-2 rounded-full bg-[#32cd32] flex-shrink-0 mt-1.5"
                            aria-hidden="true"
                        ></span>
                    </div>
                </Link>
            </div>

            <div
                v-else
                class="sp-glass-card rounded-2xl p-12 text-center border border-dashed"
                style="border-color: var(--sp-border);"
            >
                <span class="material-symbols-outlined text-4xl mb-4 block" style="color: var(--sp-text-3);">notifications_off</span>
                <p class="font-semibold mb-1">You're all caught up</p>
                <p class="text-sm" style="color: var(--sp-text-2);">Likes, follows, and comments will show up here.</p>
            </div>
        </main>

        <ToastContainer />
    </div>
</template>

<style scoped>
.sp-notification-card:hover {
    transform: translateY(-1px);
}

.sp-notification-card--unread {
    border-color: color-mix(in srgb, var(--sp-primary) 35%, var(--sp-border));
}

.sp-notification-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--sp-primary-soft);
    color: var(--sp-primary);
    flex-shrink: 0;
}
</style>
