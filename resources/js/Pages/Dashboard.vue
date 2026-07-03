<script setup>
import { useForm, router } from '@inertiajs/vue3';

// Receive both props
defineProps({ posts: Array, suggestedUsers: Array });

const form = useForm({ content: '' });

const submit = () => {
    form.post(route('posts.store'), { onSuccess: () => form.reset() });
};

const logout = () => router.post(route('logout'));

const follow = (userId) => {
    router.post(route('users.follow', userId), {}, { preserveScroll: true });
};
</script>

<template>
    <div class="min-h-screen bg-white">
        <header class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-gray-100 py-4 px-6 flex justify-between items-center z-10">
            <h1 class="font-bold text-xl tracking-tighter uppercase italic">Social Pulse</h1>
            <button @click="logout" class="text-xs font-semibold uppercase hover:text-gray-500 transition-colors">Logout</button>
        </header>

        <!-- Use a Grid to separate Feed and Sidebar -->
        <main class="max-w-4xl mx-auto py-8 px-4 grid grid-cols-1 md:grid-cols-3 gap-12">
            
            <!-- LEFT: Feed -->
            <div class="md:col-span-2">
                <form @submit.prevent="submit" class="mb-12">
                    <textarea v-model="form.content" rows="3" class="w-full p-4 border border-gray-200 rounded-xl focus:border-black transition-all resize-none" placeholder="What's happening?"></textarea>
                    <div class="flex justify-end mt-3">
                        <button type="submit" class="bg-black text-white px-6 py-2 rounded-full text-sm font-semibold hover:bg-gray-800">Post</button>
                    </div>
                </form>

                <div class="space-y-8">
                    <div v-for="post in posts" :key="post.id" class="border-b border-gray-100 pb-8">
                        <p class="font-bold text-sm mb-1">@{{ post.user.username }}</p>
                        <p class="text-lg leading-relaxed">{{ post.content }}</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Sidebar (Suggestions) -->
            <aside class="hidden md:block">
                <h2 class="font-bold text-sm uppercase tracking-widest mb-6">Suggested</h2>
                <div v-if="suggestedUsers.length > 0" class="space-y-4">
                    <div v-for="user in suggestedUsers" :key="user.id" class="flex items-center justify-between">
                        <div>
                            <p class="font-bold text-sm">{{ user.name }}</p>
                            <p class="text-xs text-gray-400">@{{ user.username }}</p>
                        </div>
                        <button @click="follow(user.id)" class="text-[10px] font-bold uppercase border border-black px-3 py-1 rounded-full hover:bg-black hover:text-white transition-all">
                            Follow
                        </button>
                    </div>
                </div>
                <p v-else class="text-xs text-gray-400">No new suggestions.</p>
            </aside>
            
        </main>
    </div>
</template>