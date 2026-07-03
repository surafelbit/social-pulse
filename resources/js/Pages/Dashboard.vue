<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    posts: Array,
    suggestedUsers: Array,
});

// Post creation form
const form = useForm({ content: '' });

const submit = () => {
    form.post(route('posts.store'), {
        onSuccess: () => form.reset(),
    });
};

// Logout
const logout = () => router.post(route('logout'));

// --- Like Logic ---
const likingPostId = ref(null);

const toggleLike = (post) => {
    if (likingPostId.value === post.id) return; // prevent double-click
    likingPostId.value = post.id;

    if (post.isLiked) {
        router.delete(route('likes.destroy', post.id), {
            preserveScroll: true,
            onFinish: () => { likingPostId.value = null; },
        });
    } else {
        router.post(route('likes.store'), { post_id: post.id }, {
            preserveScroll: true,
            onFinish: () => { likingPostId.value = null; },
        });
    }
};

// --- Follow Logic ---
const followingUserId = ref(null);

const followUser = (user) => {
    if (followingUserId.value === user.id) return;
    followingUserId.value = user.id;

    router.post(route('users.follow', user.id), {}, {
        preserveScroll: true,
        onFinish: () => { followingUserId.value = null; },
    });
};
const submitComment = (post) => {
    router.post(route('posts.comments.store', post.id), { 
        content: post.newComment 
    }, { 
        preserveScroll: true,
        onSuccess: () => post.newComment = '' 
    });
};
</script>

<template>
    <div class="min-h-screen bg-[#0d0d0d] text-white font-body">

        <!-- ── Header ── -->
        <header class="sticky top-0 z-50 bg-[#0d0d0d]/80 backdrop-blur-xl border-b border-white/5">
            <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#32cd32] shadow-[0_0_12px_rgba(50,205,50,0.7)]"></span>
                    <span class="font-headline text-headline-md font-bold text-white tracking-tight">Social Pulse</span>
                </div>

                <!-- Nav actions -->
                <div class="flex items-center gap-5">
                    <a href="#" class="text-sm text-white/50 hover:text-white transition-colors hidden md:block">Home</a>
                    <a href="#" class="text-sm text-white/50 hover:text-white transition-colors hidden md:block">Explore</a>
                    <button
                        @click="logout"
                        id="logout-btn"
                        class="text-xs font-semibold uppercase tracking-wider text-white/50 hover:text-red-400 transition-colors"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </header>

        <!-- ── Main 2-column layout ── -->
        <main class="max-w-6xl mx-auto px-4 md:px-6 py-8 grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8 items-start">

            <!-- ── LEFT: Feed ── -->
            <div class="space-y-6">

                <!-- Post Composer -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-5 shadow-lg">
                    <form @submit.prevent="submit">
                        <textarea
                            id="post-composer"
                            v-model="form.content"
                            rows="3"
                            maxlength="280"
                            placeholder="What's on your mind?"
                            class="w-full bg-transparent text-white placeholder-white/30 text-base resize-none focus:outline-none leading-relaxed"
                        ></textarea>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-white/10">
                            <span class="text-xs text-white/30">{{ form.content.length }}/280</span>
                            <button
                                type="submit"
                                id="post-submit-btn"
                                :disabled="form.processing || !form.content.trim()"
                                class="bg-[#32cd32] text-black text-sm font-bold px-6 py-2 rounded-full transition-all hover:bg-[#28a828] hover:shadow-[0_0_20px_rgba(50,205,50,0.35)] disabled:opacity-40 disabled:cursor-not-allowed active:scale-95"
                            >
                                {{ form.processing ? 'Posting…' : 'Post' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Empty state -->
                <div v-if="!posts || posts.length === 0" class="text-center py-16 text-white/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                    </svg>
                    <p class="text-sm">No posts yet. Follow someone to see their posts here.</p>
                </div>

                <!-- Feed posts -->
                <div v-else class="space-y-4">
                    <article
                        v-for="post in posts"
                        :key="post.id"
                        class="group bg-white/5 border border-white/10 rounded-2xl p-5 transition-all hover:border-white/20 hover:bg-white/[0.07]"
                    >
                        <!-- User info row -->
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#32cd32]/40 to-[#006e0a]/60 flex items-center justify-center flex-shrink-0 text-xs font-bold text-white uppercase">
                                {{ post.user?.name?.charAt(0) ?? '?' }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white leading-tight">{{ post.user?.name }}</p>
                                <p class="text-xs text-white/40">@{{ post.user?.username }}</p>
                            </div>
                        </div>

                        <!-- Content -->
                        <p class="text-base text-white/90 leading-relaxed mb-4">{{ post.content }}</p>

                        <!-- Actions row -->
                        <div class="flex items-center gap-6 pt-3 border-t border-white/5">
                            <!-- Like button -->
                            <button
                                :id="`like-btn-${post.id}`"
                                @click="toggleLike(post)"
                                :disabled="likingPostId === post.id"
                                class="flex items-center gap-1.5 text-xs font-medium transition-all"
                                :class="post.isLiked
                                    ? 'text-red-400 hover:text-red-300'
                                    : 'text-white/40 hover:text-white'"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 transition-transform"
                                    :class="post.isLiked ? 'fill-red-400 scale-110' : 'fill-none'"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                                </svg>
                                <span>{{ post.likes_count }}</span>
                            </button>

                            <!-- Timestamp placeholder -->
                            <span class="text-xs text-white/20 ml-auto">Just now</span>
                        </div>
                        <!-- Inside your post loop, after the Like button -->
<div class="mt-4 border-t border-gray-100 pt-4">
        <!-- List Comments -->
    <div v-for="comment in post.comments" :key="comment.id" class="text-sm mb-2">
<span class="font-bold text-black">@{{ comment.user.username }}:</span> 
        <span class="text-gray-700">{{ comment.content }}</span>    </div>

    <!-- Comment Input -->
    <form @submit.prevent="submitComment(post)" class="mt-3 flex gap-2">
        <input 
            v-model="post.newComment" 
            placeholder="Write a comment..." 
            class="flex-1 text-sm bg-white border border-gray-200 rounded-full px-4 py-2 text-black placeholder-gray-400 focus:border-black focus:ring-0 transition-all"
        >
        <button 
            type="submit" 
            class="text-xs font-bold uppercase text-black hover:text-gray-500 transition-colors"
        >
            Post
        </button>
    </form>
</div>
                    </article>
                </div>

            </div><!-- end LEFT -->

            <!-- ── RIGHT: Suggestions Sidebar ── -->
            <aside class="hidden lg:block">
                <div class="sticky top-24 bg-white/5 border border-white/10 rounded-2xl p-5">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-white/40 mb-4">Who to follow</h2>

                    <!-- No suggestions -->
                    <div v-if="!suggestedUsers || suggestedUsers.length === 0" class="text-center py-6 text-white/30 text-sm">
                        You're following everyone! 🎉
                    </div>

                    <!-- Suggestions list -->
                    <ul v-else class="space-y-4">
                        <li
                            v-for="user in suggestedUsers"
                            :key="user.id"
                            class="flex items-center gap-3"
                        >
                            <!-- Avatar -->
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#32cd32]/30 to-[#006e0a]/50 flex items-center justify-center flex-shrink-0 text-xs font-bold text-white uppercase">
                                {{ user.name?.charAt(0) ?? '?' }}
                            </div>

                            <!-- Name / username -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate">{{ user.name }}</p>
                                <p class="text-xs text-white/40 truncate">@{{ user.username }}</p>
                            </div>

                            <!-- Follow button -->
                            <button
                                :id="`follow-btn-${user.id}`"
                                @click="followUser(user)"
                                :disabled="followingUserId === user.id"
                                class="flex-shrink-0 text-xs font-bold px-3 py-1.5 rounded-full border border-[#32cd32]/60 text-[#32cd32] hover:bg-[#32cd32] hover:text-black transition-all disabled:opacity-50 active:scale-95"
                            >
                                {{ followingUserId === user.id ? '…' : 'Follow' }}
                            </button>
                        </li>
                    </ul>
                </div>
            </aside>

        </main>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap');

* { box-sizing: border-box; }

.font-body { font-family: 'Inter', sans-serif; }
.font-headline { font-family: 'Hanken Grotesk', sans-serif; }
</style>