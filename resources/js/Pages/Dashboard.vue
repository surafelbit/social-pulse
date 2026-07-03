<script setup>
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
defineProps({ posts: Array });

const form = useForm({ content: '' });

const submit = () => {
    form.post(route('posts.store'), {
        onSuccess: () => form.reset(),
    });
};
const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
<header class="flex justify-between items-center py-6 px-4 border-b mb-8">
        <h1 class="font-bold text-xl">Social Pulse</h1>
        <button @click="logout" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
            Logout
        </button>
    </header>
    <div class="max-w-xl mx-auto mt-10 p-4">
        <!-- Input -->
        <form @submit.prevent="submit" class="mb-8">
            <textarea 
                v-model="form.content" 
                class="w-full p-3 border rounded-lg" 
                placeholder="Share your moment..."
            ></textarea>
            <button 
                type="submit" 
                :disabled="form.processing"
                class="mt-2 bg-green-600 text-white px-6 py-2 rounded-lg"
            >
                Post
            </button>
        </form>

        <!-- Feed List -->
        <div class="space-y-4">
            <div v-for="post in posts" :key="post.id" class="p-4 border rounded-lg">
                <p class="font-bold text-sm text-gray-600">@{{ post.user.username }}</p>
                <p class="text-base">{{ post.content }}</p>
            </div>
        </div>
    </div>
</template>