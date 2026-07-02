<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    canLogin: { type: Boolean },
    canRegister: { type: Boolean },
});

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <Head title="Welcome to Social Pulse" />

    <div class="min-h-screen bg-white text-black dark:bg-black dark:text-white flex flex-col items-center justify-center font-sans">
        
        <!-- Header -->
        <header class="mb-12 text-center">
            <h1 class="text-4xl font-bold tracking-tight">Social Pulse</h1>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Connect, share, and stay updated.</p>
        </header>

        <!-- Navigation Actions -->
        <nav v-if="canLogin" class="flex flex-col gap-4 w-full max-w-xs">
            <template v-if="$page.props.auth.user">
                <Link
                    href="/dashboard"
                    class="px-6 py-3 text-center border border-black dark:border-white hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-all"
                >
                    Dashboard
                </Link>
                <button
                    @click="logout"
                    class="px-6 py-3 text-center bg-black text-white dark:bg-white dark:text-black hover:opacity-80 transition-all cursor-pointer"
                >
                    Log out
                </button>
            </template>

            <template v-else>
                <Link
                    href="/login"
                    class="px-6 py-3 text-center border border-black dark:border-white hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-all"
                >
                    Log in
                </Link>
                
                <Link
                    v-if="canRegister"
                    href="/register"
                    class="px-6 py-3 text-center bg-black text-white dark:bg-white dark:text-black hover:opacity-80 transition-all"
                >
                    Register
                </Link>
            </template>
        </nav>

    </div>
</template>