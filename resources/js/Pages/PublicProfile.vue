<script setup>
import { router, Link } from '@inertiajs/vue3';
defineProps({ profileUser: Object, posts: Array, isFollowing: Boolean });

const follow = (userId) => {
    router.post(route('users.follow', userId), {}, { 
        preserveScroll: true 
    });
};
</script>

<template>
    <div class="min-h-screen bg-white max-w-2xl mx-auto py-12 px-6">
        <h1 class="text-4xl font-bold tracking-tighter">{{ profileUser.name }}</h1>
        <p class="text-gray-500 mb-6">@{{ profileUser.username }}</p>

        <button 
            v-if="profileUser.id !== $page.props.auth.user.id"
            @click="follow(profileUser.id)"
            class="mb-12 border border-black px-6 py-2 rounded-full text-sm font-bold uppercase hover:bg-black hover:text-white transition-all"
        >
            {{ isFollowing ? 'Following' : 'Follow' }}
        </button>

        <div class="space-y-8">
            <div v-for="post in posts" :key="post.id" class="border-b border-gray-100 pb-8">
                <p class="text-lg leading-relaxed">{{ post.content }}</p>
            </div>
        </div>
    </div>
</template>