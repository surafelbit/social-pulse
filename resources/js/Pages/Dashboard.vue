<script setup>
import { router } from '@inertiajs/vue3';

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <div class="p-12">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p class="mt-4">Welcome to your Social Pulse feed.</p>

        <!-- Logout Button -->
        <button 
            @click="logout"
            class="mt-8 px-4 py-2 border border-black dark:border-white hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-all"
        >
            Log out
        </button>
    </div>
</template>