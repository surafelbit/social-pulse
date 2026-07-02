<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Receive the posts passed from PostController
defineProps({
    posts: Array
});

const form = useForm({
    content: '',
});

const submit = () => {
    form.post(route('posts.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Feed</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                
                <!-- Post Creation Form -->
                <div class="bg-white p-6 shadow-sm sm:rounded-lg mb-6">
                    <form @submit.prevent="submit">
                        <textarea 
                            v-model="form.content" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500"
                            placeholder="What's happening?"
                        ></textarea>
                        <button type="submit" class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded">Post</button>
                    </form>
                </div>

                <!-- Feed Display -->
                <div v-for="post in posts" :key="post.id" class="bg-white p-6 mb-4 shadow-sm sm:rounded-lg">
                    <h3 class="font-bold text-gray-900">{{ post.user.name }}</h3>
                    <p class="text-gray-700 mt-2">{{ post.content }}</p>
                    <small class="text-gray-500">{{ new Date(post.created_at).toLocaleString() }}</small>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>