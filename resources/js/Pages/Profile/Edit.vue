<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});

const isDark = ref(
    typeof window !== 'undefined'
        ? document.documentElement.classList.contains('dark')
        : false
);

const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('sp-theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('sp-theme', 'light');
    }
};

const logout = () => router.post(route('logout'));
</script>

<template>
    <Head title="Edit Profile | Social Pulse" />

    <div class="min-h-screen transition-colors duration-300" style="background-color: var(--sp-bg); color: var(--sp-text);">

        <!-- ── HEADER ──────────────────────────────────── -->
        <header class="sticky top-0 z-50 sp-header">
            <div class="max-w-3xl mx-auto px-4 md:px-6 h-16 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <Link href="/dashboard" class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[#32cd32]/10 hover:text-[#32cd32]"
                        style="color: var(--sp-text-2); border: 1px solid var(--sp-border);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                        </svg>
                    </Link>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#32cd32] shadow-[0_0_10px_rgba(50,205,50,0.6)]"></span>
                        <span class="font-headline font-bold text-lg tracking-tight" style="color: var(--sp-text);">
                            Social<span class="sp-gradient-text">Pulse</span>
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        @click="toggleTheme"
                        id="theme-toggle-profile"
                        class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[#32cd32]/10 hover:text-[#32cd32]"
                        style="color: var(--sp-text-2); border: 1px solid var(--sp-border);"
                    >
                        <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                        </svg>
                    </button>
                    <button
                        @click="logout"
                        class="hidden sm:flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide transition-colors hover:text-red-400"
                        style="color: var(--sp-text-2);"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </header>

        <!-- ── PAGE CONTENT ─────────────────────────────── -->
        <div class="max-w-3xl mx-auto px-4 md:px-6 py-8 space-y-5">

            <!-- Page heading -->
            <div class="flex items-center gap-3 mb-2">
                <span class="w-1 h-5 rounded-full bg-[#32cd32]"></span>
                <h1 class="text-sm font-bold uppercase tracking-widest" style="color: var(--sp-text-3);">Edit Profile</h1>
            </div>

            <!-- Update Profile Info -->
            <div class="sp-glass-card p-6 overflow-hidden relative animate-rise-in">
                <div class="sp-accent-bar mb-5 rounded-full"></div>
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <!-- Update Password -->
            <div class="sp-glass-card p-6 overflow-hidden relative animate-slide-up" style="animation-delay: 0.1s;">
                <div class="sp-accent-bar mb-5 rounded-full"></div>
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <!-- Delete Account -->
            <div class="sp-glass-card p-6 overflow-hidden relative animate-slide-up" style="animation-delay: 0.2s; border-color: rgba(239, 68, 68, 0.2);">
                <div class="h-0.5 w-full rounded-full bg-gradient-to-r from-red-500/60 to-red-700/30 mb-5"></div>
                <DeleteUserForm class="max-w-xl" />
            </div>

        </div>
    </div>
</template>
