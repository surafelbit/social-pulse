<script setup>
import { ref, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import UserSearch from '../Components/UserSearch.vue';
import ToastContainer from '../Components/ToastContainer.vue';

const props = defineProps({
    profileUser: Object,
    posts: Array,
    isFollowing: Boolean,
    followers: Array,
    following: Array
});

if (typeof window !== 'undefined' && !window.showToast) {
    window.showToast = (message, type = 'success') => {
        window.dispatchEvent(new CustomEvent('show-toast', { detail: { message, type } }));
    };
}

const isEditingBio = ref(false);
const bioText = ref(props.profileUser.bio || '');
const isSavingBio = ref(false);

// Tab state
const activeTab = ref('posts');
const mediaPosts = computed(() => (props.posts ?? []).filter(p => p.image_path));

// Bio
const startEditingBio = () => {
    bioText.value = props.profileUser.bio || '';
    isEditingBio.value = true;
};
const saveBio = () => {
    isSavingBio.value = true;
    router.patch(route('profile.bio.update'), { bio: bioText.value }, {
        preserveScroll: true,
        onSuccess: () => {
            isEditingBio.value = false;
            props.profileUser.bio = bioText.value;
            window.showToast("Bio updated successfully!");
        },
        onFinish: () => { isSavingBio.value = false; }
    });
};

// Follow
const follow = (userId) => {
    router.post(route('users.follow', userId), {}, {
        preserveScroll: true,
        onSuccess: () => {
            window.showToast(props.isFollowing ? "Unfollowed user!" : "Followed user!");
        }
    });
};

// Message
const startChat = (userId) => {
    router.post(route('conversations.store'), { recipient_id: userId }, {
        preserveScroll: true,
    });
};

const goBack = () => window.history.back();

// Lightbox
const lightboxSrc = ref(null);
const openLightbox = (src) => { lightboxSrc.value = src; };
const closeLightbox = () => { lightboxSrc.value = null; };

// Followers/Following Modal State
const activeModal = ref(null); // 'followers' or 'following' or null
const modalSearchQuery = ref('');

const openModal = (type) => {
    activeModal.value = type;
    modalSearchQuery.value = '';
};

const closeModal = () => {
    activeModal.value = null;
    modalSearchQuery.value = '';
};

const filteredModalUsers = computed(() => {
    const list = activeModal.value === 'followers' ? (props.followers ?? []) : (props.following ?? []);
    if (!modalSearchQuery.value.trim()) return list;
    const q = modalSearchQuery.value.toLowerCase();
    return list.filter(u =>
        u.name?.toLowerCase().includes(q) ||
        u.username?.toLowerCase().includes(q)
    );
});

const toggleFollowUser = (user) => {
    router.post(route('users.follow', user.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            window.showToast(user.isFollowing ? `Unfollowed @${user.username}` : `Followed @${user.username}`);
        }
    });
};
</script>

<template>
    <div class="min-h-screen transition-colors duration-300" style="background-color: var(--sp-bg); color: var(--sp-text);">

        <!-- HEADER -->
        <header class="sticky top-0 z-50 sp-header">
            <div class="max-w-3xl mx-auto px-4 md:px-6 h-16 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button @click="goBack" class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[#32cd32]/10 hover:text-[#32cd32]" style="color: var(--sp-text-2); border: 1px solid var(--sp-border);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    </button>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#32cd32] shadow-[0_0_10px_rgba(50,205,50,0.6)]"></span>
                        <Link href="/dashboard" class="font-headline font-bold text-lg tracking-tight" style="color: var(--sp-text);">Social<span class="sp-gradient-text">Pulse</span></Link>
                    </div>
                </div>
                <UserSearch />
                <div class="flex items-center gap-3">
                    <Link href="/dashboard" class="text-xs font-semibold uppercase tracking-wide transition-colors hover:text-[#32cd32]" style="color: var(--sp-text-2);">Feed</Link>
                    <Link :href="route('notifications.index')" class="relative flex items-center justify-center w-8 h-8 rounded-full transition-colors hover:bg-[#32cd32]/10" aria-label="Notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--sp-text-2);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            v-if="$page.props.auth.unreadNotifications > 0"
                            class="absolute -top-0.5 -right-0.5 min-w-[1rem] h-4 px-1 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center leading-none"
                        >
                            {{ $page.props.auth.unreadNotifications > 9 ? '9+' : $page.props.auth.unreadNotifications }}
                        </span>
                    </Link>
                </div>
            </div>
        </header>

        <!-- PROFILE BODY -->
        <main class="max-w-3xl mx-auto px-4 md:px-6 py-8">

            <!-- Profile hero card -->
            <div class="sp-glass-card overflow-hidden mb-6 animate-rise-in">
                <div class="relative h-24 overflow-hidden" style="background: linear-gradient(135deg, #0d2e0d 0%, #1a4d1a 50%, #0d2e0d 100%);">
                    <div class="absolute -top-6 -left-6 w-24 h-24 rounded-full opacity-30 blur-2xl" style="background: #32cd32;"></div>
                    <div class="absolute -bottom-4 right-10 w-20 h-20 rounded-full opacity-20 blur-2xl" style="background: #4ce346;"></div>
                    <div class="absolute bottom-0 left-0 right-0 h-0.5 sp-accent-bar"></div>
                </div>
                <div class="px-6 pb-6">
                    <div class="flex items-end justify-between -mt-8 mb-4">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center text-xl font-bold text-white uppercase shadow-lg ring-4" style="background: linear-gradient(135deg, #32cd32 0%, #006e0a 100%); ring-color: var(--sp-bg);">
                            {{ profileUser.name?.charAt(0) ?? '?' }}
                        </div>
                        <div v-if="profileUser.id !== $page.props.auth.user.id" class="flex gap-2">
                            <button @click="startChat(profileUser.id)" class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-all duration-200 bg-blue-600 text-white hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Message
                            </button>
                            <button @click="follow(profileUser.id)" :id="`public-follow-btn-${profileUser.id}`" class="flex items-center gap-2 px-5 py-2 rounded-full text-sm font-bold transition-all duration-200" :class="isFollowing ? 'border-2 border-[#32cd32] text-[#32cd32] hover:bg-red-500/10 hover:border-red-400 hover:text-red-400' : 'sp-btn-primary'">
                                <svg v-if="isFollowing" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                {{ isFollowing ? 'Following' : 'Follow' }}
                            </button>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold font-headline tracking-tight mb-0.5" style="color: var(--sp-text);">{{ profileUser.name }}</h1>
                    <p class="text-sm font-medium" style="color: var(--sp-text-3);">@{{ profileUser.username }}</p>
                    <!-- Bio -->
                    <div class="mt-3">
                        <div v-if="!isEditingBio">
                            <p class="text-sm leading-relaxed" style="color: var(--sp-text-2);">{{ profileUser.bio || "No bio yet." }}</p>
                            <button v-if="profileUser.id === $page.props.auth.user.id" @click="startEditingBio" class="mt-2 text-xs font-bold uppercase tracking-wider text-[#32cd32] hover:text-[#28a828] transition-colors">Edit Bio</button>
                        </div>
                        <div v-else class="space-y-2">
                            <textarea v-model="bioText" rows="2" maxlength="160" placeholder="Tell us about yourself..." class="sp-input text-sm w-full" style="background-color: var(--sp-bg-2);"></textarea>
                            <div class="flex items-center gap-2">
                                <button @click="saveBio" :disabled="isSavingBio" class="sp-btn-primary text-xs py-1.5 px-4">{{ isSavingBio ? "Saving..." : "Save" }}</button>
                                <button @click="isEditingBio = false" class="sp-btn-outline text-xs py-1 px-3 border-gray-400 text-gray-400 hover:bg-gray-400/10 hover:text-gray-300">Cancel</button>
                                <span class="text-xs font-mono text-[var(--sp-text-3)] ml-auto">{{ bioText.length }}/160</span>
                            </div>
                        </div>
                    </div>
                    <!-- Stats -->
                    <div class="flex items-center justify-around gap-2 mt-4 pt-4" style="border-top: 1px solid var(--sp-border);">
                        <div class="text-center flex-1">
                            <div class="text-lg font-bold text-[#32cd32]">{{ posts?.length ?? 0 }}</div>
                            <div class="text-[10px] uppercase tracking-widest" style="color: var(--sp-text-3);">Posts</div>
                        </div>
                        <div class="w-px h-6" style="background-color: var(--sp-border);"></div>
                        <button @click="openModal('followers')" class="text-center flex-1 hover:opacity-80 transition-opacity focus:outline-none">
                            <div class="text-lg font-bold text-[#32cd32]">{{ followers?.length ?? 0 }}</div>
                            <div class="text-[10px] uppercase tracking-widest" style="color: var(--sp-text-3);">Followers</div>
                        </button>
                        <div class="w-px h-6" style="background-color: var(--sp-border);"></div>
                        <button @click="openModal('following')" class="text-center flex-1 hover:opacity-80 transition-opacity focus:outline-none">
                            <div class="text-lg font-bold text-[#32cd32]">{{ following?.length ?? 0 }}</div>
                            <div class="text-[10px] uppercase tracking-widest" style="color: var(--sp-text-3);">Following</div>
                        </button>
                        <div class="w-px h-6" style="background-color: var(--sp-border);"></div>
                        <div class="text-center flex-1">
                            <div class="text-lg font-bold text-[#32cd32]">{{ posts?.reduce((s, p) => s + (p.likes_count ?? 0), 0) ?? 0 }}</div>
                            <div class="text-[10px] uppercase tracking-widest" style="color: var(--sp-text-3);">Likes</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABS -->
            <div class="flex items-center gap-1 mb-6 p-1 rounded-xl" style="background-color: var(--sp-bg-2); border: 1px solid var(--sp-border);">
                <button @click="activeTab = 'posts'" class="flex-1 flex items-center justify-center gap-2 py-2 px-4 rounded-lg text-sm font-semibold transition-all duration-200" :class="activeTab === 'posts' ? 'text-white shadow-lg' : 'hover:text-[#32cd32]'" :style="activeTab === 'posts' ? 'background: linear-gradient(135deg, #32cd32, #006e0a); color: white;' : 'color: var(--sp-text-3);'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    Posts
                </button>
                <button @click="activeTab = 'media'" class="flex-1 flex items-center justify-center gap-2 py-2 px-4 rounded-lg text-sm font-semibold transition-all duration-200" :class="activeTab === 'media' ? 'text-white shadow-lg' : 'hover:text-[#32cd32]'" :style="activeTab === 'media' ? 'background: linear-gradient(135deg, #32cd32, #006e0a); color: white;' : 'color: var(--sp-text-3);'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    Media
                    <span v-if="mediaPosts.length > 0" class="text-[10px] font-bold px-1.5 py-0.5 rounded-full" :style="activeTab === 'media' ? 'background: rgba(255,255,255,0.25); color: white;' : 'background: var(--sp-primary-glow); color: #32cd32;'">{{ mediaPosts.length }}</span>
                </button>
            </div>

            <!-- POSTS TAB -->
            <div v-if="activeTab === 'posts'">
                <div v-if="!posts || posts.length === 0" class="text-center py-16 sp-glass-card">
                    <div class="text-4xl mb-3">??</div>
                    <p class="font-semibold mb-1" style="color: var(--sp-text);">No posts yet</p>
                    <p class="text-sm" style="color: var(--sp-text-3);">{{ profileUser.name }} hasn't posted anything yet.</p>
                </div>
                <div v-else class="space-y-4">
                    <article v-for="post in posts" :key="post.id" class="sp-glass-card p-5 group animate-rise-in">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 sp-avatar">{{ profileUser.name?.charAt(0) ?? '?' }}</div>
                            <div>
                                <p class="text-sm font-semibold leading-tight" style="color: var(--sp-text);">{{ profileUser.name }}</p>
                                <p class="text-xs" style="color: var(--sp-text-3);">@{{ profileUser.username }}</p>
                            </div>
                        </div>
                        <p class="text-base leading-relaxed" style="color: var(--sp-text);">{{ post.content }}</p>
                        <!-- Post image -->
                        <div v-if="post.image_path" class="mt-3 rounded-2xl overflow-hidden cursor-pointer relative" style="max-height: 24rem;" @click="openLightbox(`/storage/${post.image_path}`)">
                            <img :src="`/storage/${post.image_path}`" alt="Post image" class="w-full object-cover transition-transform duration-300 hover:scale-[1.02]" />
                            <div class="absolute inset-0 opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-center justify-center rounded-2xl" style="background: rgba(0,0,0,0.35);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6"/></svg>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 mt-4 pt-3" style="border-top: 1px solid var(--sp-border);">
                            <span class="flex items-center gap-1.5 text-xs" style="color: var(--sp-text-3);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 fill-red-400" viewBox="0 0 24 24"><path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                                {{ post.likes_count ?? 0 }} likes
                            </span>
                            <span v-if="post.image_path" class="flex items-center gap-1 text-xs px-2 py-0.5 rounded-full" style="background: var(--sp-primary-glow); color: #32cd32;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/></svg>
                                Photo
                            </span>
                        </div>
                    </article>
                </div>
            </div>

            <!-- MEDIA TAB -->
            <div v-if="activeTab === 'media'">
                <div v-if="mediaPosts.length === 0" class="text-center py-16 sp-glass-card">
                    <div class="text-4xl mb-3">???</div>
                    <p class="font-semibold mb-1" style="color: var(--sp-text);">No media yet</p>
                    <p class="text-sm" style="color: var(--sp-text-3);">{{ profileUser.name }} hasn't shared any photos yet.</p>
                </div>
                <div v-else class="media-grid">
                    <div v-for="post in mediaPosts" :key="post.id" class="media-grid-item" @click="openLightbox(`/storage/${post.image_path}`)">
                        <img :src="`/storage/${post.image_path}`" :alt="post.content || 'Post image'" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110" />
                        <div class="media-overlay">
                            <div class="flex items-center gap-4 text-white text-sm font-semibold">
                                <span class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-red-400" viewBox="0 0 24 24"><path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                                    {{ post.likes_count ?? 0 }}
                                </span>
                            </div>
                            <p v-if="post.content" class="text-white/80 text-xs px-3 text-center line-clamp-2">{{ post.content }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </main>

        <!-- LIGHTBOX -->
        <Transition name="lightbox">
            <div v-if="lightboxSrc" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.85); backdrop-filter: blur(8px);" @click.self="closeLightbox">
                <button @click="closeLightbox" class="absolute top-4 right-4 w-10 h-10 rounded-full flex items-center justify-center text-white transition-all hover:bg-white/10" style="border: 1px solid rgba(255,255,255,0.2);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <img :src="lightboxSrc" alt="Full image" class="lightbox-img max-w-full max-h-[90vh] object-contain rounded-2xl shadow-2xl" />
            </div>
        </Transition>

        <!-- FOLLOWERS/FOLLOWING MODAL -->
        <Transition name="modal-fade">
            <div v-if="activeModal" class="fixed inset-0 z-[90] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md" @click.self="closeModal">
                <div class="w-full max-w-md rounded-2xl overflow-hidden shadow-2xl sp-glass-card animate-scale-up" style="background-color: var(--sp-card); border: 1px solid var(--sp-border);">
                    <!-- Modal Header -->
                    <div class="px-5 py-4 flex items-center justify-between border-b" style="border-color: var(--sp-border);">
                        <h3 class="font-headline font-bold text-lg capitalize" style="color: var(--sp-text);">
                            {{ activeModal }}
                        </h3>
                        <button @click="closeModal" class="w-8 h-8 rounded-full flex items-center justify-center transition-all hover:bg-red-500/10 hover:text-red-400" style="color: var(--sp-text-3); border: 1px solid var(--sp-border);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="p-4 border-b" style="border-color: var(--sp-border);">
                        <div class="relative">
                            <input
                                v-model="modalSearchQuery"
                                type="text"
                                :placeholder="`Search ${activeModal}...`"
                                class="w-full sp-input pl-10 pr-4 py-2 text-sm rounded-xl focus:outline-none"
                                style="background-color: var(--sp-bg-2); border-color: var(--sp-border);"
                            />
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--sp-text-3);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Modal Body / User List -->
                    <div class="max-h-[350px] overflow-y-auto p-4 space-y-4">
                        <div v-if="filteredModalUsers.length === 0" class="text-center py-10">
                            <div class="text-3xl mb-2">🔍</div>
                            <p class="text-sm font-semibold" style="color: var(--sp-text);">No users found</p>
                            <p class="text-xs mt-1" style="color: var(--sp-text-3);">Try searching for another name or username.</p>
                        </div>
                        <div v-else class="space-y-3.5">
                            <div v-for="user in filteredModalUsers" :key="user.id" class="flex items-center justify-between gap-3">
                                <!-- User info link -->
                                <Link
                                    :href="route('profile.show', user.username)"
                                    @click="closeModal"
                                    class="flex items-center gap-3 hover:opacity-85 transition-opacity flex-1 min-w-0"
                                >
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white uppercase shadow-sm flex-shrink-0" style="background: linear-gradient(135deg, #32cd32 0%, #006e0a 100%);">
                                        {{ user.name?.charAt(0) ?? '?' }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold truncate" style="color: var(--sp-text);">
                                            {{ user.name }}
                                        </p>
                                        <p class="text-xs truncate" style="color: var(--sp-text-3);">
                                            @{{ user.username }}
                                        </p>
                                    </div>
                                </Link>

                                <!-- Action button: follow/unfollow (exclude logged in user) -->
                                <button
                                    v-if="user.id !== $page.props.auth.user.id"
                                    @click="toggleFollowUser(user)"
                                    class="px-4 py-1.5 rounded-full text-xs font-bold transition-all duration-200 flex-shrink-0"
                                    :class="user.isFollowing ? 'border border-[#32cd32] text-[#32cd32] hover:bg-red-500/10 hover:border-red-400 hover:text-red-400' : 'sp-btn-primary'"
                                >
                                    {{ user.isFollowing ? 'Following' : 'Follow' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

        <ToastContainer />
    </div>
</template>

<style scoped>
.media-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 4px;
    border-radius: 16px;
    overflow: hidden;
}
.media-grid-item {
    position: relative;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background-color: var(--sp-bg-2);
    cursor: pointer;
}
.media-overlay {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 60%, transparent 100%);
}
.media-grid-item:hover .media-overlay {
    opacity: 1;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.lightbox-enter-active,
.lightbox-leave-active {
    transition: opacity 0.25s ease;
}
.lightbox-enter-from,
.lightbox-leave-to {
    opacity: 0;
}
.lightbox-img {
    animation: lb-zoom-in 0.25s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}
@keyframes lb-zoom-in {
    from { transform: scale(0.85); opacity: 0; }
    to   { transform: scale(1);    opacity: 1; }
}

.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.25s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}
.animate-scale-up {
    animation: modal-scale-up 0.25s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}
@keyframes modal-scale-up {
    from { transform: scale(0.9); opacity: 0; }
    to   { transform: scale(1);    opacity: 1; }
}
</style>
