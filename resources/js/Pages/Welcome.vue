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
    <Head title="Social Pulse | Real-time Connections" />

    <div class="min-h-screen bg-white dark:bg-black text-black dark:text-white font-sans selection:bg-black selection:text-white dark:selection:bg-white dark:selection:text-black">
        
        <!-- Navbar -->
        <nav class="flex justify-between items-center py-6 px-12 border-b border-gray-100 dark:border-gray-900">
            <div class="text-xl font-bold tracking-tighter">SOCIAL PULSE</div>
            <div class="flex gap-4">
                <template v-if="$page.props.auth.user">
                    <Link href="/dashboard" class="text-sm hover:underline">Dashboard</Link>
                    <button @click="logout" class="text-sm font-bold">Logout</button>
                </template>
                <template v-else>
                    <Link href="/login" class="text-sm hover:underline">Log in</Link>
                    <Link v-if="canRegister" href="/register" class="text-sm font-bold">Register</Link>
                </template>
            </div>
        </nav>

        <!-- Main Hero -->
        <main class="max-w-6xl mx-auto px-6 py-24">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                
                <!-- Text Content -->
                <div class="space-y-8">
                    <h1 class="text-7xl font-extrabold tracking-tighter leading-none">
                        Real-time<br />conversations.
                    </h1>
                    <p class="text-xl text-gray-500 dark:text-gray-400 max-w-md">
                        A high-performance social platform built for speed, clarity, and meaningful interaction.
                    </p>
                    <div class="flex gap-4">
                        <Link v-if="!$page.props.auth.user" href="/register" class="px-8 py-4 bg-black text-white dark:bg-white dark:text-black font-semibold rounded-full hover:opacity-80 transition">
                            Get Started
                        </Link>
                    </div>
                </div>

                <!-- Feature Grid (The "Details") -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-8 border border-gray-100 dark:border-gray-900 rounded-3xl hover:border-black dark:hover:border-white transition">
                        <h3 class="font-bold mb-2">Live Updates</h3>
                        <p class="text-sm text-gray-500">Instant synchronization across all your devices.</p>
                    </div>
                    <div class="p-8 border border-gray-100 dark:border-gray-900 rounded-3xl hover:border-black dark:hover:border-white transition">
                        <h3 class="font-bold mb-2">Secure</h3>
                        <p class="text-sm text-gray-500">Enterprise-grade authentication and privacy.</p>
                    </div>
                    <div class="p-8 border border-gray-100 dark:border-gray-900 rounded-3xl hover:border-black dark:hover:border-white transition">
                        <h3 class="font-bold mb-2">Minimalist</h3>
                        <p class="text-sm text-gray-500">Clutter-free interface focused on the content.</p>
                    </div>
                    <div class="p-8 border border-gray-100 dark:border-gray-900 rounded-3xl hover:border-black dark:hover:border-white transition">
                        <h3 class="font-bold mb-2">Fast</h3>
                        <p class="text-sm text-gray-500">Built with modern, optimized architecture.</p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="py-12 px-12 text-center text-xs text-gray-400">
            &copy; 2026 Social Pulse. Engineered for the modern web.
        </footer>
    </div>
</template>