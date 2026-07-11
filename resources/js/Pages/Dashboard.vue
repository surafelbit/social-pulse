<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import UserSearch from "../Components/UserSearch.vue";
import ToastContainer from "../Components/ToastContainer.vue";
import { usePage } from '@inertiajs/vue3';
// Header scroll shadow
const scrolled = ref(false);
const handleScroll = () => {
    scrolled.value = window.scrollY > 8;
};
onMounted(() => window.addEventListener("scroll", handleScroll, { passive: true }));
onUnmounted(() => window.removeEventListener("scroll", handleScroll));
onMounted(() => {
    // Only attempt to listen if a user is securely logged in
    if (page.props.auth?.user) {
        window.Echo.private(`App.Models.User.${page.props.auth.user.id}`)
            .listen('.notification.sent', (e) => {
                // Magically update the global state value
                page.props.auth.unreadNotifications++;
                
                // Optional: Trigger an optional browser alert or audio sound here
                console.log('Real-time notification arrived!', e.notification);
            });
    }
});

onUnmounted(() => {
    if (page.props.auth?.user) {
        window.Echo.leave(`App.Models.User.${page.props.auth.user.id}`);
    }
});

if (typeof window !== "undefined") {
    window.showToast = (message, type = "success") => {
        window.dispatchEvent(
            new CustomEvent("show-toast", { detail: { message, type } }),
        );
    };
}

const selectedImagePreview = ref("");

const props = defineProps({
    posts: Array,
    suggestedUsers: Array,
});

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

if (typeof window !== "undefined") {
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
}

const form = useForm({ content: "", image: null });
const submit = () => {
    form.post(route("posts.store"), {
        onSuccess: () => {
            form.reset();
            selectedImagePreview.value = "";
            window.showToast("Post shared successfully!");
        },
    });
};

const handleImageSelection = (event) => {
    const file = event.target.files?.[0] ?? null;
    form.image = file;

    if (selectedImagePreview.value) {
        URL.revokeObjectURL(selectedImagePreview.value);
    }

    selectedImagePreview.value = file ? URL.createObjectURL(file) : "";
};

// Logout
const logout = () => router.post(route("logout"));

// Like logic
const likingPostId = ref(null);
const toggleLike = (post) => {
    if (likingPostId.value === post.id) return;
    likingPostId.value = post.id;
    if (post.isLiked) {
        router.delete(route("likes.destroy", post.id), {
            preserveScroll: true,
            onSuccess: () => {
                window.showToast("Removed like");
            },
            onFinish: () => {
                likingPostId.value = null;
            },
        });
    } else {
        router.post(
            route("likes.store"),
            { post_id: post.id },
            {
                preserveScroll: true,
                onSuccess: () => {
                    window.showToast("Post liked!");
                },
                onFinish: () => {
                    likingPostId.value = null;
                },
            },
        );
    }
};

// Follow logic
const followingUserId = ref(null);
const followUser = (user) => {
    if (followingUserId.value === user.id) return;
    followingUserId.value = user.id;
    router.post(
        route("users.follow", user.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                window.showToast(`Followed @${user.username}!`);
            },
            onFinish: () => {
                followingUserId.value = null;
            },
        },
    );
};

// Comment logic
const submitComment = (post) => {
    router.post(
        route("posts.comments.store", post.id),
        {
            content: post.newComment,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                post.newComment = "";
                window.showToast("Comment posted!");
            },
        },
    );
};

// ── Edit / Delete ─────────────────────────────────────────────
const openMenuPostId = ref(null);
const editingPost = ref(null);   // { id, content } — null when not editing
const deletingPostId = ref(null);

const toggleMenu = (postId) => {
    openMenuPostId.value = openMenuPostId.value === postId ? null : postId;
};

const startEdit = (post) => {
    editingPost.value = { id: post.id, content: post.content };
    openMenuPostId.value = null;
};

const cancelEdit = () => { editingPost.value = null; };

const saveEdit = (post) => {
    router.put(
        route('posts.update', post.id),
        { content: editingPost.value.content },
        {
            preserveScroll: true,
            onSuccess: () => {
                post.content = editingPost.value.content;
                editingPost.value = null;
                window.showToast('Post updated!');
            },
        },
    );
};

const confirmDelete = (postId) => {
    deletingPostId.value = postId;
    openMenuPostId.value = null;
};

const cancelDelete = () => { deletingPostId.value = null; };

const deletePost = (postId) => {
    router.delete(
        route('posts.destroy', postId),
        {
            preserveScroll: true,
            onSuccess: () => {
                deletingPostId.value = null;
                window.showToast('Post deleted.', 'error');
            },
        },
    );
};
</script>

<template>
    <div
        class="min-h-screen transition-colors duration-300"
        style="background-color: var(--sp-bg); color: var(--sp-text)"
    >
        <!-- ── HEADER ─────────────────────────────────── -->
        <header class="sticky top-0 z-50 sp-header" :class="{ 'sp-header--scrolled': scrolled }">
            <div
                class="max-w-6xl mx-auto px-4 md:px-6 h-16 flex items-center justify-between gap-4"
            >
                <!-- Logo -->
                <div class="flex items-center gap-2.5">
                    <span
                        class="w-2.5 h-2.5 rounded-full bg-[#32cd32] shadow-[0_0_12px_rgba(50,205,50,0.7)] animate-pulse-glow"
                    ></span>
                    <span
                        class="font-headline font-bold text-lg tracking-tight"
                        style="color: var(--sp-text)"
                    >
                        Social<span class="sp-gradient-text">Pulse</span>
                    </span>
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
                        id="theme-toggle"
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

                    <!-- Logout -->
                    <button
                        @click="logout"
                        id="logout-btn"
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
                    <Link :href="route('conversations.index')" class="relative flex items-center justify-center w-9 h-9 rounded-full transition-colors hover:bg-white/5" aria-label="Messages">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/60 hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </Link>
                    <Link :href="route('notifications.index')" class="relative flex items-center justify-center w-9 h-9 rounded-full transition-colors hover:bg-white/5" aria-label="Notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/60 hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            v-if="$page.props.auth.unreadNotifications > 0"
                            class="absolute -top-0.5 -right-0.5 min-w-[1.125rem] h-[1.125rem] px-1 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none"
                        >
                            {{ $page.props.auth.unreadNotifications > 9 ? '9+' : $page.props.auth.unreadNotifications }}
                        </span>
                    </Link>
                </nav>
            </div>
        </header>

        <!-- ── MAIN 2-COLUMN LAYOUT ────────────────────── -->
        <main
            class="max-w-6xl mx-auto px-4 md:px-6 py-8 grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-6 items-start"
        >
            <!-- ── LEFT: FEED ─────────────────────────── -->
            <div class="space-y-5">
                <!-- Post Composer -->
                <div class="sp-glass-card sp-composer p-5 shadow-sm animate-rise-in">
                    <!-- Lime accent top bar -->
                    <div class="sp-accent-bar sp-accent-shimmer mb-4 rounded-full"></div>

                    <form @submit.prevent="submit">
                        <div class="flex items-start gap-3">
                            <!-- Author avatar -->
                            <div class="w-9 h-9 sp-avatar flex-shrink-0">
                                {{
                                    $page.props.auth.user.name?.charAt(0) ?? "?"
                                }}
                            </div>

                            <textarea
                                id="post-composer"
                                v-model="form.content"
                                rows="3"
                                maxlength="280"
                                placeholder="What's on your mind?"
                                class="flex-1 bg-transparent resize-none focus:outline-none leading-relaxed text-base placeholder-[var(--sp-text-3)] transition-colors"
                                style="color: var(--sp-text)"
                            ></textarea>
                        </div>

                        <div
                            v-if="selectedImagePreview"
                            class="mt-3 rounded-xl overflow-hidden border"
                            style="border-color: var(--sp-border)"
                        >
                            <img
                                :src="selectedImagePreview"
                                alt="Selected preview"
                                class="w-full max-h-72 object-cover"
                            />
                        </div>

                        <div
                            class="flex items-center justify-between mt-4 pt-3"
                            style="border-top: 1px solid var(--sp-border)"
                        >
                            <div class="flex items-center gap-2">
                                <label
                                    class="flex items-center gap-2 cursor-pointer text-sm"
                                    style="color: var(--sp-text-3)"
                                >
                                    <svg
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
                                            d="M6.75 6.75A2.25 2.25 0 0 1 9 4.5h6a2.25 2.25 0 0 1 2.25 2.25v.75M6.75 6.75H4.5a2.25 2.25 0 0 0-2.25 2.25v8.25A2.25 2.25 0 0 0 4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V9A2.25 2.25 0 0 0 19.5 6.75h-2.25"
                                        />
                                    </svg>
                                    <span>Add photo</span>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        class="hidden"
                                        @change="handleImageSelection"
                                    />
                                </label>
                                <span
                                    class="text-xs font-mono"
                                    style="color: var(--sp-text-3)"
                                >
                                    {{ form.content.length
                                    }}<span style="color: var(--sp-border-2)"
                                        >/280</span
                                    >
                                </span>
                                <!-- Character usage bar -->
                                <div
                                    class="w-16 h-1 rounded-full overflow-hidden"
                                    style="background-color: var(--sp-border)"
                                >
                                    <div
                                        class="h-full rounded-full transition-all duration-300"
                                        :style="{
                                            width:
                                                (form.content.length / 280) *
                                                    100 +
                                                '%',
                                            backgroundColor:
                                                form.content.length > 250
                                                    ? '#ef4444'
                                                    : '#32cd32',
                                        }"
                                    ></div>
                                </div>
                            </div>

                            <button
                                type="submit"
                                id="post-submit-btn"
                                :disabled="
                                    form.processing ||
                                    (!form.content.trim() && !form.image)
                                "
                                class="sp-btn-primary text-sm px-6 py-2"
                            >
                                {{ form.processing ? "Posting…" : "Post" }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Empty state -->
                <div
                    v-if="!posts || posts.length === 0"
                    class="text-center py-20 animate-rise-in"
                >
                    <div
                        class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center sp-empty-icon"
                        style="
                            background-color: var(--sp-card);
                            border: 1px solid var(--sp-border);
                        "
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-7 h-7"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                            style="color: var(--sp-text-3)"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"
                            />
                        </svg>
                    </div>
                    <p class="font-semibold mb-1" style="color: var(--sp-text)">
                        Nothing here yet
                    </p>
                    <p class="text-sm" style="color: var(--sp-text-3)">
                        Follow someone to see their posts in your feed.
                    </p>
                </div>

                <!-- Feed posts -->
                <TransitionGroup v-else name="feed" tag="div" class="space-y-4">
                    <article
                        v-for="post in posts"
                        :key="post.id"
                        class="sp-glass-card sp-post-card p-5 group"
                    >
                        <!-- User info row -->
                        <div class="flex items-center justify-between mb-4">
                            <Link
                                :href="route('profile.show', post.user.username)"
                                class="flex items-center gap-3 hover:opacity-80 transition-opacity"
                            >
                                <div class="w-9 h-9 sp-avatar">
                                    {{ post.user?.name?.charAt(0) ?? "?" }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold leading-tight" style="color: var(--sp-text)">
                                        {{ post.user?.name }}
                                    </p>
                                    <p class="text-xs" style="color: var(--sp-text-3)">
                                        @{{ post.user?.username }}
                                    </p>
                                </div>
                            </Link>

                            <!-- 3-dot menu — only for post owner -->
                            <div v-if="post.user?.id === $page.props.auth.user.id" class="relative">
                                <button
                                    @click.stop="toggleMenu(post.id)"
                                    class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[#32cd32]/10"
                                    style="color: var(--sp-text-3);"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                    </svg>
                                </button>
                                <!-- Dropdown -->
                                <Transition name="dropdown">
                                    <div
                                        v-if="openMenuPostId === post.id"
                                        class="absolute right-0 top-9 z-20 w-36 rounded-2xl overflow-hidden shadow-2xl"
                                        style="background: var(--sp-card); border: 1px solid var(--sp-border);"
                                    >
                                        <button
                                            @click="startEdit(post)"
                                            class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-left transition-colors hover:bg-[#32cd32]/10 hover:text-[#32cd32]"
                                            style="color: var(--sp-text);"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                            </svg>
                                            Edit
                                        </button>
                                        <button
                                            @click="confirmDelete(post.id)"
                                            class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-left transition-colors hover:bg-red-500/10 hover:text-red-400"
                                            style="color: var(--sp-text-3);"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </Transition>
                            </div>
                        </div>

                        <!-- Inline edit mode -->
                        <div v-if="editingPost?.id === post.id" class="mb-4">
                            <textarea
                                v-model="editingPost.content"
                                rows="3"
                                maxlength="280"
                                class="sp-input text-sm w-full"
                                style="background-color: var(--sp-bg-2);"
                            ></textarea>
                            <div class="flex items-center gap-2 mt-2">
                                <button @click="saveEdit(post)" class="sp-btn-primary text-xs py-1.5 px-4">Save</button>
                                <button @click="cancelEdit" class="sp-btn-outline text-xs py-1 px-3 border-gray-400 text-gray-400 hover:bg-gray-400/10">Cancel</button>
                                <span class="text-xs font-mono ml-auto" style="color: var(--sp-text-3);">{{ editingPost.content?.length ?? 0 }}/280</span>
                            </div>
                        </div>

                        <!-- Content (read mode) -->
                        <p
                            v-else-if="post.content"
                            class="text-base leading-relaxed mb-4"
                            style="color: var(--sp-text)"
                        >
                            {{ post.content }}
                        </p>
                        <div
                            v-if="post.image_url"
                            class="w-full rounded-xl overflow-hidden mb-4 sp-post-image-wrap"
                        >
                            <img
                                :src="post.image_url"
                                alt="Post image"
                                class="w-full object-cover sp-post-image"
                            />
                        </div>

                        <!-- Actions row -->
                        <div
                            class="flex items-center gap-5 pt-3"
                            style="border-top: 1px solid var(--sp-border)"
                        >
                            <!-- Like button -->
                            <button
                                :id="`like-btn-${post.id}`"
                                @click="toggleLike(post)"
                                :disabled="likingPostId === post.id"
                                class="flex items-center gap-1.5 text-xs font-medium transition-all duration-200 hover:scale-105"
                                :class="post.isLiked ? 'text-red-400 sp-like-pop' : ''"
                                :style="
                                    !post.isLiked
                                        ? { color: 'var(--sp-text-3)' }
                                        : {}
                                "
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 transition-transform duration-200"
                                    :class="
                                        post.isLiked
                                            ? 'fill-red-400 scale-110'
                                            : 'fill-none'
                                    "
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
                                    />
                                </svg>
                                <span>{{ post.likes_count }}</span>
                            </button>

                            <!-- Comment count badge -->
                            <span
                                class="flex items-center gap-1.5 text-xs font-medium"
                                style="color: var(--sp-text-3)"
                            >
                                <svg
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
                                        d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"
                                    />
                                </svg>
                                {{ post.comments?.length ?? 0 }}
                            </span>

                            <span
                                class="text-xs ml-auto"
                                style="color: var(--sp-text-3)"
                                >Just now</span
                            >
                        </div>

                        <!-- Comments section -->
                        <div
                            class="mt-4 pt-4 space-y-2"
                            style="border-top: 1px solid var(--sp-border)"
                        >
                            <!-- Existing comments -->
                            <div
                                v-for="comment in post.comments"
                                :key="comment.id"
                                class="flex items-start gap-2.5"
                            >
                                <div
                                    class="w-6 h-6 sp-avatar text-[9px] flex-shrink-0"
                                >
                                    {{ comment.user?.name?.charAt(0) ?? "?" }}
                                </div>
                                <div
                                    class="flex-1 rounded-xl px-3 py-2 text-sm"
                                    style="
                                        background-color: var(--sp-bg-2);
                                        border: 1px solid var(--sp-border);
                                    "
                                >
                                    <span
                                        class="font-bold text-[#32cd32] text-xs"
                                        >@{{ comment.user.username }}</span
                                    >
                                    <span
                                        class="ml-1.5"
                                        style="color: var(--sp-text)"
                                        >{{ comment.content }}</span
                                    >
                                </div>
                            </div>

                            <!-- Comment input -->
                            <form
                                @submit.prevent="submitComment(post)"
                                class="flex items-center gap-2 mt-3"
                            >
                                <div
                                    class="w-6 h-6 sp-avatar text-[9px] flex-shrink-0"
                                >
                                    {{
                                        $page.props.auth.user.name?.charAt(0) ??
                                        "?"
                                    }}
                                </div>
                                <input
                                    v-model="post.newComment"
                                    placeholder="Write a comment…"
                                    class="sp-input flex-1 text-sm py-2 px-3 rounded-full"
                                    style="background-color: var(--sp-bg-2)"
                                />
                                <button
                                    type="submit"
                                    class="text-xs font-bold uppercase tracking-wide text-[#32cd32] hover:text-[#28a828] transition-colors px-1"
                                >
                                    Post
                                </button>
                            </form>
                        </div>
                    </article>
                </TransitionGroup>
            </div>
            <!-- end LEFT -->

            <!-- ── RIGHT: SIDEBAR ─────────────────────── -->
            <aside class="hidden lg:block">
                <div class="sticky top-24 space-y-5">
                    <!-- Who to follow -->
                    <div class="sp-glass-card sp-side-card p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <span
                                class="w-1 h-4 rounded-full bg-[#32cd32]"
                            ></span>
                            <h2
                                class="text-xs font-bold uppercase tracking-widest"
                                style="color: var(--sp-text-3)"
                            >
                                Who to follow
                            </h2>
                        </div>

                        <!-- No suggestions -->
                        <div
                            v-if="
                                !suggestedUsers || suggestedUsers.length === 0
                            "
                            class="text-center py-6 text-sm"
                            style="color: var(--sp-text-3)"
                        >
                            <div class="text-2xl mb-2">🎉</div>
                            You're following everyone!
                        </div>

                        <!-- Suggestions list -->
                        <ul v-else class="space-y-4">
                            <li
                                v-for="user in suggestedUsers"
                                :key="user.id"
                                class="flex items-center gap-3 sp-suggest-row"
                            >
                                <div class="w-9 h-9 sp-avatar">
                                    {{ user.name?.charAt(0) ?? "?" }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-sm font-semibold truncate"
                                        style="color: var(--sp-text)"
                                    >
                                        {{ user.name }}
                                    </p>
                                    <p
                                        class="text-xs truncate"
                                        style="color: var(--sp-text-3)"
                                    >
                                        @{{ user.username }}
                                    </p>
                                </div>

                                <!-- Follow button -->
                                <button
                                    :id="`follow-btn-${user.id}`"
                                    @click="followUser(user)"
                                    :disabled="followingUserId === user.id"
                                    class="sp-btn-outline flex-shrink-0 text-[11px] inline-flex items-center gap-1.5"
                                >
                                    <svg
                                        v-if="followingUserId === user.id"
                                        class="w-3 h-3 sp-spin"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="40 20" opacity="0.85"/>
                                    </svg>
                                    <span>{{ followingUserId === user.id ? "Following" : "Follow" }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Quick stats card -->
                    <div class="sp-glass-card sp-side-card p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <span
                                class="w-1 h-4 rounded-full bg-[#32cd32]"
                            ></span>
                            <h2
                                class="text-xs font-bold uppercase tracking-widest"
                                style="color: var(--sp-text-3)"
                            >
                                Your Stats
                            </h2>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div
                                class="rounded-lg p-3 text-center sp-stat-tile"
                                style="
                                    background-color: var(--sp-bg-2);
                                    border: 1px solid var(--sp-border);
                                "
                            >
                                <div class="text-lg font-bold text-[#32cd32]">
                                    {{ posts?.length ?? 0 }}
                                </div>
                                <div
                                    class="text-[10px] uppercase tracking-widest mt-0.5"
                                    style="color: var(--sp-text-3)"
                                >
                                    Posts
                                </div>
                            </div>
                            <div
                                class="rounded-lg p-3 text-center sp-stat-tile"
                                style="
                                    background-color: var(--sp-bg-2);
                                    border: 1px solid var(--sp-border);
                                "
                            >
                                <div class="text-lg font-bold text-[#32cd32]">
                                    {{
                                        posts?.reduce(
                                            (s, p) => s + (p.likes_count ?? 0),
                                            0,
                                        ) ?? 0
                                    }}
                                </div>
                                <div
                                    class="text-[10px] uppercase tracking-widest mt-0.5"
                                    style="color: var(--sp-text-3)"
                                >
                                    Likes
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </main>
        <ToastContainer />

        <!-- Delete confirmation modal -->
        <Transition name="modal-fade">
            <div
                v-if="deletingPostId !== null"
                class="fixed inset-0 z-[90] flex items-center justify-center p-4"
                style="background: rgba(0,0,0,0.6); backdrop-filter: blur(6px);"
                @click.self="cancelDelete"
            >
                <div
                    class="w-full max-w-sm rounded-2xl p-6 shadow-2xl sp-modal-pop"
                    style="background: var(--sp-card); border: 1px solid var(--sp-border);"
                >
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 sp-shake-icon" style="background: rgba(239,68,68,0.15);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                        </svg>
                    </div>
                    <h3 class="text-center font-bold text-base mb-1" style="color: var(--sp-text);">Delete post?</h3>
                    <p class="text-center text-sm mb-6" style="color: var(--sp-text-3);">This action cannot be undone.</p>
                    <div class="flex gap-3">
                        <button
                            @click="cancelDelete"
                            class="flex-1 py-2 rounded-xl text-sm font-semibold transition-colors"
                            style="background: var(--sp-bg-2); color: var(--sp-text); border: 1px solid var(--sp-border);"
                        >Cancel</button>
                        <button
                            @click="deletePost(deletingPostId)"
                            class="flex-1 py-2 rounded-xl text-sm font-semibold text-white transition-colors"
                            style="background: linear-gradient(135deg, #ef4444, #b91c1c);"
                        >Delete</button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
/* Pulse animation */
@keyframes pulse-glow {
    0%, 100% { opacity: 0.6; }
    50%       { opacity: 1;   }
}
.animate-pulse-glow {
    animation: pulse-glow 2s ease-in-out infinite;
}

/* Dropdown transition */
.dropdown-enter-active,
.dropdown-leave-active {
    transition: opacity 0.15s ease, transform 0.15s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-6px) scale(0.97);
}

/* Modal fade */
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}

/* ── Header scroll shadow ─────────────────────────────── */
.sp-header {
    transition: box-shadow 0.3s ease, backdrop-filter 0.3s ease, border-color 0.3s ease;
    border-bottom: 1px solid transparent;
}
.sp-header--scrolled {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
    border-bottom-color: var(--sp-border);
}

/* ── Composer polish ──────────────────────────────────── */
.sp-composer {
    transition: box-shadow 0.3s ease, border-color 0.3s ease;
}
.sp-composer:focus-within {
    border-color: rgba(50, 205, 50, 0.4);
    box-shadow: 0 0 0 3px rgba(50, 205, 50, 0.1), 0 12px 30px rgba(0, 0, 0, 0.12);
}
.sp-accent-shimmer {
    position: relative;
    overflow: hidden;
    background-size: 200% 100%;
    animation: sp-shimmer-slide 5s linear infinite;
}
@keyframes sp-shimmer-slide {
    0% { background-position: 0% 0; }
    100% { background-position: -200% 0; }
}

.feed-move,
.feed-enter-active,
.feed-leave-active {
    transition: all 0.45s cubic-bezier(0.4, 0, 0.2, 1);
}
.feed-enter-from {
    opacity: 0;
    transform: translateY(18px) scale(0.98);
}
.feed-leave-to {
    opacity: 0;
    transform: translateX(24px) scale(0.97);
}
.feed-leave-active {
    position: absolute;
    width: 100%;
}

/* ── Post card hover lift ─────────────────────────────── */
.sp-post-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}
.sp-post-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.16);
    border-color: rgba(50, 205, 50, 0.25);
}

/* ── Post image zoom ───────────────────────────────────── */
.sp-post-image-wrap {
    position: relative;
}
.sp-post-image {
    max-height: 28rem;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.sp-post-image-wrap:hover .sp-post-image {
    transform: scale(1.04);
}

/* ── Like pop ──────────────────────────────────────────── */
@keyframes sp-like-pop {
    0%   { transform: scale(1); }
    35%  { transform: scale(1.35); }
    60%  { transform: scale(0.92); }
    100% { transform: scale(1); }
}
.sp-like-pop svg {
    animation: sp-like-pop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* ── Empty state breathing icon ────────────────────────── */
@keyframes sp-breathe {
    0%, 100% { transform: scale(1); opacity: 0.85; }
    50%      { transform: scale(1.08); opacity: 1; }
}
.sp-empty-icon {
    animation: sp-breathe 2.6s ease-in-out infinite;
}

/* ── Sidebar hover polish ──────────────────────────────── */
.sp-side-card {
    transition: box-shadow 0.3s ease, border-color 0.3s ease;
}
.sp-side-card:hover {
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
    border-color: rgba(50, 205, 50, 0.2);
}
.sp-suggest-row {
    padding: 6px;
    margin: -6px;
    border-radius: 12px;
    transition: background-color 0.2s ease, transform 0.2s ease;
}
.sp-suggest-row:hover {
    background-color: rgba(50, 205, 50, 0.06);
    transform: translateX(2px);
}
.sp-stat-tile {
    transition: transform 0.2s ease, border-color 0.2s ease;
}
.sp-stat-tile:hover {
    transform: translateY(-2px);
    border-color: rgba(50, 205, 50, 0.35) !important;
}

/* ── Follow button spinner ─────────────────────────────── */
.sp-spin {
    animation: sp-rotate 0.8s linear infinite;
}
@keyframes sp-rotate {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}

/* ── Modal entrance ────────────────────────────────────── */
@keyframes sp-modal-pop {
    0%   { opacity: 0; transform: translateY(12px) scale(0.94); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}
.sp-modal-pop {
    animation: sp-modal-pop 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@keyframes sp-shake {
    0%, 100% { transform: rotate(0deg); }
    25%      { transform: rotate(-8deg); }
    75%      { transform: rotate(8deg); }
}
.sp-shake-icon {
    animation: sp-shake 0.5s ease-in-out 0.15s 1;
}

/* ── Reduced motion ────────────────────────────────────── */
@media (prefers-reduced-motion: reduce) {
    .animate-pulse-glow,
    .sp-accent-shimmer,
    .sp-empty-icon,
    .sp-spin,
    .sp-shake-icon,
    .sp-like-pop svg,
    .sp-modal-pop,
    .feed-enter-active,
    .feed-leave-active {
        animation: none !important;
        transition: none !important;
    }
}
</style>