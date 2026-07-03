<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';

const props = defineProps({ profileUser: Object, posts: Array, isFollowing: Boolean });

// Dark mode awareness (reads state set by Dashboard)
const isDark = ref(
    typeof window !== 'undefined'
        ? document.documentElement.classList.contains('dark')
        : false
);

const follow = (userId) => {
    router.post(route('users.follow', userId), {}, {
        preserveScroll: true
    });
};

const goBack = () => window.history.back();
</script>

<template>
    <div class="min-h-screen transition-colors duration-300" style="background-color: var(--sp-bg); color: var(--sp-text);">

        <!-- ── HEADER ──────────────────────────────────── -->
        <header class="sticky top-0 z-50 sp-header">
            <div class="max-w-3xl mx-auto px-4 md:px-6 h-16 flex items-center justify-between gap-4">
                <!-- Back + Logo -->
                <div class="flex items-center gap-3">
                    <button
                        @click="goBack"
                        class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[#32cd32]/10 hover:text-[#32cd32]"
                        style="color: var(--sp-text-2); border: 1px solid var(--sp-border);"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                        </svg>
                    </button>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#32cd32] shadow-[0_0_10px_rgba(50,205,50,0.6)]"></span>
                        <Link href="/dashboard" class="font-headline font-bold text-lg tracking-tight" style="color: var(--sp-text);">
                            Social<span class="sp-gradient-text">Pulse</span>
                        </Link>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <Link href="/dashboard" class="text-xs font-semibold uppercase tracking-wide transition-colors hover:text-[#32cd32]" style="color: var(--sp-text-2);">
                        Feed
                    </Link>
                </div>
            </div>
        </header>

        <!-- ── PROFILE BODY ────────────────────────────── -->
        <main class="max-w-3xl mx-auto px-4 md:px-6 py-8">

            <!-- Profile hero card -->
            <div class="sp-glass-card overflow-hidden mb-6 animate-rise-in">
                <!-- Cover / decorative band -->
                <div class="relative h-24 overflow-hidden" style="background: linear-gradient(135deg, #0d2e0d 0%, #1a4d1a 50%, #0d2e0d 100%);">
                    <!-- Animated lime orbs -->
                    <div class="absolute -top-6 -left-6 w-24 h-24 rounded-full opacity-30 blur-2xl" style="background: #32cd32;"></div>
                    <div class="absolute -bottom-4 right-10 w-20 h-20 rounded-full opacity-20 blur-2xl" style="background: #4ce346;"></div>
                    <!-- Shimmer bar at bottom -->
                    <div class="absolute bottom-0 left-0 right-0 h-0.5 sp-accent-bar"></div>
                </div>

                <div class="px-6 pb-6">
                    <!-- Avatar -->
                    <div class="flex items-end justify-between -mt-8 mb-4">
                        <div
                            class="w-16 h-16 rounded-full flex items-center justify-center text-xl font-bold text-white uppercase shadow-lg ring-4"
                            style="background: linear-gradient(135deg, #32cd32 0%, #006e0a 100%); ring-color: var(--sp-bg);"
                        >
                            {{ profileUser.name?.charAt(0) ?? '?' }}
                        </div>

                        <!-- Follow / Unfollow button -->
                        <button
                            v-if="profileUser.id !== $page.props.auth.user.id"
                            @click="follow(profileUser.id)"
                            :id="`public-follow-btn-${profileUser.id}`"
                            class="flex items-center gap-2 px-5 py-2 rounded-full text-sm font-bold transition-all duration-200"
                            :class="isFollowing
                                ? 'border-2 border-[#32cd32] text-[#32cd32] hover:bg-red-500/10 hover:border-red-400 hover:text-red-400'
                                : 'sp-btn-primary'"
                        >
                            <svg v-if="isFollowing" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                            {{ isFollowing ? 'Following' : 'Follow' }}
                        </button>
                    </div>

                    <!-- Name + username -->
                    <h1 class="text-2xl font-bold font-headline tracking-tight mb-0.5" style="color: var(--sp-text);">
                        {{ profileUser.name }}
                    </h1>
                    <p class="text-sm font-medium" style="color: var(--sp-text-3);">@{{ profileUser.username }}</p>

                    <!-- Quick stats row -->
                    <div class="flex items-center gap-6 mt-4 pt-4" style="border-top: 1px solid var(--sp-border);">
                        <div class="text-center">
                            <div class="text-lg font-bold text-[#32cd32]">{{ posts?.length ?? 0 }}</div>
                            <div class="text-[10px] uppercase tracking-widest" style="color: var(--sp-text-3);">Posts</div>
                        </div>
                        <div class="w-px h-6" style="background-color: var(--sp-border);"></div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-[#32cd32]">
                                {{ posts?.reduce((s, p) => s + (p.likes_count ?? 0), 0) ?? 0 }}
                            </div>
                            <div class="text-[10px] uppercase tracking-widest" style="color: var(--sp-text-3);">Likes</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Posts heading -->
            <div class="flex items-center gap-3 mb-4">
                <span class="w-1 h-5 rounded-full bg-[#32cd32]"></span>
                <h2 class="text-sm font-bold uppercase tracking-widest" style="color: var(--sp-text-3);">
                    Posts by {{ profileUser.name?.split(' ')[0] }}
                </h2>
            </div>

            <!-- Empty state -->
            <div v-if="!posts || posts.length === 0" class="text-center py-16 sp-glass-card">
                <div class="text-4xl mb-3">✍️</div>
                <p class="font-semibold mb-1" style="color: var(--sp-text);">No posts yet</p>
                <p class="text-sm" style="color: var(--sp-text-3);">{{ profileUser.name }} hasn't posted anything yet.</p>
            </div>

            <!-- Posts list -->
            <div v-else class="space-y-4">
                <article
                    v-for="post in posts"
                    :key="post.id"
                    class="sp-glass-card p-5 group"
                >
                    <!-- User info -->
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 sp-avatar">
                            {{ profileUser.name?.charAt(0) ?? '?' }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold leading-tight" style="color: var(--sp-text);">{{ profileUser.name }}</p>
                            <p class="text-xs" style="color: var(--sp-text-3);">@{{ profileUser.username }}</p>
                        </div>
                    </div>

                    <!-- Content -->
                    <p class="text-base leading-relaxed" style="color: var(--sp-text);">{{ post.content }}</p>

                    <!-- Bottom actions -->
                    <div class="flex items-center gap-4 mt-4 pt-3" style="border-top: 1px solid var(--sp-border);">
                        <span class="flex items-center gap-1.5 text-xs" style="color: var(--sp-text-3);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 fill-red-400" viewBox="0 0 24 24">
                                <path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                            </svg>
                            {{ post.likes_count ?? 0 }} likes
                        </span>
                    </div>
                </article>
            </div>

        </main>
    </div>
</template>